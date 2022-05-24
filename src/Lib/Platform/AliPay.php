<?php

namespace App\Payment\Lib\Platform;


use Magein\Payment\Pay;

class AliPay extends Pay
{
    /**
     * @param null $key
     * @return array|mixed
     */
    public function setAccount($key = null)
    {
        $account = parent::setAccount($key);
        $this->setOptions();
        return $account;
    }

    public function getPlatformName()
    {
        return static::PLATFORM_ALI;
    }

    public function noticeData(array $data, PayOrder $payOrder): NoticeData
    {
        $created_at = $data['gmt_create'] ?? '';
        if (empty($created_at)) {
            $created_at = $data['send_pay_date'] ?? '';
        }

        $pay_amount = $data['buyer_pay_amount'] ?? '';
        if ($pay_amount <= 0) {
            $pay_amount = $data['total_amount'] ?? '';
        }

        $receipt_amount = $data['receipt_amount'] ?? '';
        if ($receipt_amount <= 0) {
            $receipt_amount = $data['total_amount'] ?? '';
        }

        $pay_no = $data['out_trade_no'] ?? '';
        if (empty($pay_no)) {
            $pay_no = $payOrder->pay_no;
        }

        $notice = parent::noticeData($data, $payOrder);
        $notice->setPlatform($this->getPlatformName());
        $notice->setTradeType('h5');
        $notice->setMerchantTradeNo($pay_no);
        $notice->setPlatformTradeNo($data['trade_no'] ?? '');
        $notice->setBuyerAccount($data['buyer_logon_id'] ?? '');
        $notice->setBuyerPayAmount($pay_amount);
        $notice->setReceiptAmount($receipt_amount);
        $notice->setCreatedAt($created_at);
        $trade_status = $data['trade_status'] ?? '';
        if ($trade_status == 'TRADE_SUCCESS') {
            $notice->setTradeStatus(1);
        } else {
            $notice->setTradeStatus(0);
        }

        return $notice;
    }

    /**
     * @return string|null
     */
    public function notifyResponse(): ?string
    {
        return 'success';
    }

    /**
     * @param $data
     * @param \App\Models\PayOrder $payOrder
     * @return \App\Common\Payment\NotifyData|null
     */
    public function notify($data, PayOrder $payOrder): ?NotifyData
    {
        $result = $this->verifyNotify($data);
        if (!$result) {
            return null;
        }

        $notifyData = new NotifyData();
        $notifyData->setNotifyDate();
        $notifyData->setTradeNo($data['trade_no']);
        $notifyData->setPayNo($data['out_trade_no'] ?? $payOrder->pay_no);
        $notifyData->setNotifyData(json_encode($data));
        if (($data['trade_status'] ?? '') == 'TRADE_SUCCESS') {
            $notifyData->success();
        } else {
            $notifyData->fail();
        }

        return $notifyData;
    }

    /**
     * @param bool $setting
     * @return \Alipay\EasySDK\Kernel\Config
     */
    public function setOptions(bool $setting = true)
    {
        if (empty($this->payAccount)) {
            return null;
        }

        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';
        $options->appId = $this->payAccount->getAppid();

        // 公钥字符串
        $options->merchantPrivateKey = $this->payAccount->getMerchantPrivateKey();
        $options->alipayPublicKey = $this->payAccount->getPlatformPublicKey();
        $options->encryptKey = $this->payAccount->getEncryptKey();

        // 支出类需要使用公钥证书的方式
        $others = $this->payAccount->getOthers();
        if ($others['cert_public'] ?? '') {
            $options->alipayCertPath = $others['cert_public'] ?? '';
            $options->alipayRootCertPath = $others['cert_root'] ?? '';
            $options->merchantCertPath = $others['cert_merchant'] ?? '';
        }
        if ($setting) {
            Factory::setOptions($options);
        }
        return $options;
    }

    /**
     * 异步通知验签
     * @param $data
     * @return bool
     */
    public function verifyNotify($data): bool
    {
        if (!$data['app_id']) {
            return false;
        }
        $this->setAccount($data['app_id']);
        return Factory::payment()->common()->verifyNotify($data);
    }

    public function h5(PayData $payData)
    {
        $payData->setPlatformPayType('h5');

        if (!$this->createPayOrder($payData)) {
            return MsgContainer::msg('生成支付信息失败，请重试');
        }

        try {
            $result = Factory::payment()->wap()->asyncNotify(
                $this->getNotifyUrl($payData->getPayNo())
            )->pay(
                $payData->getSubject(),
                $payData->getPayNo(),
                $payData->getTotalAmount(),
                $payData->getQuitUrl(),
                $payData->getReturnUrl()
            );
            if ($result->body) {
                echo $result->body;
                exit();
            } else {
                $result = '';
            }
        } catch (\Exception $e) {
            $this->writeSignatureLog($e->getMessage(), $payData->getPayNo());
            $result = false;
        }

        return $result;
    }

    /**
     * @param $pay_no
     * @return array
     */
    public function queryByMerchantOrderNo($pay_no): array
    {
        if ($pay_no instanceof PayOrder) {
            $payOrder = $pay_no;
        } else {
            $payOrder = PayOrder::_payNo($pay_no);
        }

        return $this->queryOrder($payOrder->appid, $payOrder->pay_no);
    }

    /**
     * debug使用
     * @param $appid
     * @param $pay_no
     * @return array
     */
    public function queryOrder($appid, $pay_no): array
    {
        $this->setAccount($appid);

        try {
            $response = Factory::payment()->common()->query($pay_no);
            $result = $response->toMap();
            if ($result['code'] != '10000' || empty($result['out_trade_no'] ?? '')) {
                $result = [];
            }
        } catch (\Exception $exception) {
            $result = [];
        }

        return $result;
    }

    public function transferToAccount(TransferAccountData $accountData)
    {
        // 文档 https://opendocs.alipay.com/open/02byuo?scene=ca56bca529e64125a2786703c6192d41

        if (empty($this->payAccount->getAppid())) {
            $this->setAccount($this->transfer_merchant_id);
        }

        $method = 'alipay.fund.trans.uni.transfer';

        $biz_params = [
            'out_biz_no' => $accountData->getTransferNo(),
            'trans_amount' => $accountData->getAmount(),
            'product_code' => 'TRANS_ACCOUNT_NO_PWD',
            'biz_scene' => 'DIRECT_TRANSFER',
            'order_title' => $accountData->getSubject(),
            'remark' => $accountData->getRemark(),
            'payee_info' => [
                'identity_type' => 'ALIPAY_LOGON_ID',
                'identity' => $accountData->getAccount(),
                'name' => $accountData->getName(),
            ]
        ];

        try {
            $response = Factory::util()->generic()->execute($method, [], $biz_params);
            $result = $response->toMap();
            if (empty($result)) {
                return false;
            }

            if ($result['code'] != '10000') {
                $this->error = $result['sub_msg'];
                return false;
            }

            $http_body = $result['http_body'];
            $http_body = json_decode($http_body, true);
            $data = $http_body['alipay_fund_trans_uni_transfer_response'] ?? '';
            if (empty($data)) {
                $this->error = '响应参数错误';
                return false;
            }

            if ($data['status'] != 'SUCCESS') {
                $this->error = '响应转账状态非成功状态';
                return false;
            }

            return $data;

        } catch (TeaUnableRetryError $exception) {
            $this->error = $exception->getMessage();
        }

        return false;
    }

    /**
     * @param $certify_no
     * @param $name
     * @param $cert_no
     * @param int|string $cert_type
     * @return false|mixed
     */
    public function userCertifyInit($certify_no, $name, $cert_no, $cert_type = 1)
    {
        $cert_types = [
            1 => 'IDENTITY_CARD',
            2 => 'HOME_VISIT_PERMIT_HK_MC',
            3 => 'HOME_VISIT_PERMIT_TAIWAN',
            4 => 'RESIDENCE_PERMIT_HK_MC',
            5 => 'RESIDENCE_PERMIT_TAIWAN'
        ];

        if (!isset($cert_types[$cert_type])) {
            $this->error = '证件类型错误';
            return false;
        }

        if (empty($name) || empty($cert_no)) {
            $this->error = '姓名或者身份证号码不能为空';
            return false;
        }

        if (!preg_match('/^[0-9]{18}x?/', $cert_no)) {
            $this->error = '身份证号码格式不正确';
            return false;
        }

        // 文档 https://opendocs.alipay.com/open/02ahjy
        $this->setAccount($this->certify_merchant_id);

        try {
            $identity_param = new IdentityParam();
            // 固定值
            $identity_param->identityType = 'CERT_INFO';
            $identity_param->certName = $name;
            $identity_param->certNo = $cert_no;
            $identity_param->certType = $cert_types[$cert_type];
            $merchant_config = new MerchantConfig();
            $merchant_config->returnUrl = config('app.url') . 'api/user/certifyQuery/' . $certify_no;
            $response = Factory::member()->identification()->init($certify_no, 'SMART_FACE', $identity_param, $merchant_config);
            $result = $response->toMap();

            if ($result['code'] != '10000') {
                $this->error = $result['sub_msg'] ?? '';
                return false;
            }

            if (isset($result['certify_id']) && $result['certify_id']) {
                return $result['certify_id'];
            }

            $this->error = '获取验证编号错误';

        } catch (\Exception | TeaUnableRetryError | TeaError $exception) {
            $this->error = $exception->getMessage();
        }


        return false;
    }

    /**
     * @param $certify_id
     * @return false
     */
    public function userCertify($certify_id): bool
    {
        $response = Factory::member()->identification()->certify($certify_id);
        $body = $response->toMap()['body'] ?? '';
        if ($body) {
            header('location:' . $body);
            exit();
        }
        $this->error = '响应失败';
        return false;
    }

    /**
     * @param $certify_id
     * @return array
     */
    public function userCertifyQuery($certify_id): array
    {
        $this->setAccount($this->certify_merchant_id);
        $data['passed'] = 'F';
        $data['material_info'] = '{}';

        if (empty($certify_id)) {
            return $data;
        }

        try {
            $response = Factory::member()->identification()->query($certify_id);
            $body = $response->toMap();
            if ($body['code'] == '10000') {
                $data['passed'] = $body['passed'] ?? '';
                $data['material_info'] = $body['material_info'] ?? '';
            }
        } catch (\Exception | TeaUnableRetryError | TeaError $exception) {

        }
        return $data;
    }
}
