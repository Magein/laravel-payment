<?php


namespace App\Payment\Lib\Platform;

use App\Common\Payment\NoticeData;
use App\Common\Payment\NotifyData;
use App\Common\Payment\PayData;
use App\Common\Payment\Pay;

use App\Common\Signer;
use App\Common\WeChat;
use App\Models\PayOrder;
use App\Models\WxPayOauth;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Request;
use Magein\Common\MsgContainer;
use Magein\Common\RedisCache;

class WxPay extends Pay
{

    public function getPlatformName()
    {
        return static::PLATFORM_WX;
    }

    public function noticeData(array $data, PayOrder $payOrder): NoticeData
    {
        $pay_no = $data['out_trade_no'] ?? '';
        if (empty($pay_no)) {
            $pay_no = $payOrder->pay_no;
        }
        $fee = floatval($data['total_fee'] / 100);
        $notice = parent::noticeData($data, $payOrder);
        $notice->setPlatform($this->getPlatformName());
        $notice->setTradeType($data['trade_type'] ?? 'JSAPI');
        $notice->setMerchantTradeNo($pay_no);
        $notice->setPlatformTradeNo($data['transaction_id']);
        $notice->setBuyerPayAmount($fee);
        $notice->setBuyerAccount($data['openid']);
        $notice->setCreatedAt($data['time_end']);
        $notice->setReceiptAmount($fee);
        if (($data['result_code'] ?? '') == 'SUCCESS') {
            $notice->setTradeStatus(1);
        } else {
            $notice->setTradeStatus(0);
            $notice->setFailReason($data['err_code_des']);
        }
        return $notice;
    }

    /**
     * @return string|null
     */
    public function notifyResponse(): ?string
    {
        return 'SUCCESS';
    }

    /**
     * @param $data
     * @param \App\Models\PayOrder $payOrder
     * @return \App\Common\Payment\NotifyData|null
     */
    public function notify($data, PayOrder $payOrder): ?NotifyData
    {
        return new NotifyData();
    }

    /**
     * 检查是否授权，这里要记录一些参数
     * @param \Illuminate\Support\Facades\Request $request
     * @return mixed
     */
    public function checkOauth(Request $request)
    {
        // 是否传递了授权编号，传递了则用于取出open_id
        $oauth_no = $request::input('oauth_no', '');
        if (empty($oauth_no)) {
            $oauth_no = uniqid('oauth_');
            $wechat = new WeChat($this->payAccount->getAppid());
            $data = $request::only(
                [
                    'order_no',
                    'scene',
                    'total_amount',
                    'pid',
                    'subject',
                    'return_url',
                    'user_id',
                    'notify_url',
                    'pay_no',
                ]
            );
            $data['oauth_no'] = $oauth_no;
            $data['sign'] = (new Signer())->create($data);

            try {
                $params['oauth_no'] = $oauth_no;
                $params['appid'] = $this->payAccount->getAppid();
                $params['post_data'] = $data;
                RedisCache::put($oauth_no, $params, 3600);
            } catch (\Exception $exception) {

            }

            // 数据库目前作为日志使用，后续可以移除
            $model = new WxPayOauth();
            $model->oauth_no = $oauth_no;
            $model->appid = $this->payAccount->getAppid();
            $model->post_data = $data;
            $model->expired_at = now()->addMinutes(10);
            $result = $model->save();

            if ($result) {
                $redirect = $wechat->oauth($oauth_no)->redirect();
                header('location:' . $redirect);
                exit();
            }
        }

        $cache_data = RedisCache::get($oauth_no);
        if ($cache_data['open_id'] ?? '') {
            return $cache_data['open_id'];
        }

        $oauth = WxPayOauth::_oauthNo($oauth_no);
        if ($oauth && $oauth->open_id) {
            return $oauth->open_id;
        }
        return '';
    }

    /**
     * @return \EasyWeChat\Payment\Application
     */
    public function payment($appid = '', $merchant_id = '')
    {
        if ($merchant_id) {
            $this->setAccount($merchant_id);
        }

        $config = [
            'app_id' => $appid ?: $this->payAccount->getAppid(),
            'mch_id' => $merchant_id ?: $this->payAccount->getMerchantId(),
            'key' => $this->payAccount->getKey(),
        ];

        return Factory::payment($config);
    }

    public function h5(PayData $payData)
    {
        $payData->setPlatformPayType('jsapi');

        if (!$this->createPayOrder($payData)) {
            return MsgContainer::msg('生成支付信息失败，请重试');
        }

        if (!$payData->getOpenId()) {
            return MsgContainer::msg('缺少用户信息');
        }

        // https://easywechat.vercel.app/5.x/payment/order.html#%E7%BB%9F%E4%B8%80%E4%B8%8B%E5%8D%95

        // 微信文档 https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1

        // 小马哥的授权信息 oudhi6uaPh7llMN2Fs9irQc4NSE4

        $payment = $this->payment();

        $message = '';
        try {
            $result = $payment->order->unify([
                'body' => $payData->getSubject(),
                'attach' => json_encode([
                    'subject' => $payData->getSubject()
                ]),
                'out_trade_no' => $payData->getPayNo(),
                'total_fee' => $payData->getTotalAmount() * 100,
                'notify_url' => $this->getNotifyUrl($payData->getPayNo()),
                'trade_type' => 'JSAPI',
                'openid' => $payData->getOpenId()
            ]);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $result = [];
        }

        if (empty($result)) {
            $this->writeSignatureLog($message, $payData->getPayNo());
            return MsgContainer::msg('签名错误');
        }

        if (($result['return_code'] ?? '') == 'FAIL') {
            $message = $result['return_code'] . ':' . $result['return_msg'];
            $this->writeSignatureLog($message, $payData->getPayNo());
            return MsgContainer::msg('签名错误');
        }

        if (($result['result_code'] ?? '') == 'FAIL') {
            $message = $result['result_code'] . ':' . $result['err_code_des'];
            $this->writeSignatureLog($message, $payData->getPayNo());
            return MsgContainer::msg('签名错误');
        }

        $prepayId = $result['prepay_id'];

        return $payment->jssdk->bridgeConfig($prepayId);
    }

    public function queryByMerchantOrderNo($pay_no = '')
    {
        if ($pay_no instanceof PayOrder) {
            $payOrder = $pay_no;
        } else {
            $payOrder = PayOrder::_payNo($pay_no);
        }
        $appid = $payOrder->appid;
        $merchant_id = $payOrder->merchant_id;
        $pay_no = $payOrder->pay_no;

        $order = $this->payment($appid, $merchant_id)->order;

        $result = $order->queryByOutTradeNumber($pay_no);

        if (($result['result_code'] ?? '') == 'SUCCESS') {
            return $result;
        }

        return [];
    }
}
