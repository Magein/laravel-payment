<?php


namespace Magein\Payment\Lib;

use Magein\Payment\Lib\Data\PayAccountData;
use Magein\Payment\Lib\Data\PayData;
use Magein\Payment\Lib\Data\PayNotifyData;
use Magein\Payment\Models\PayOrder;

abstract class Pay
{
    /**
     * @var string
     */
    public string $error = '';

    /**
     * @var \Magein\Payment\Lib\Data\PayAccountData
     */
    public PayAccountData $payAccount;


    /**
     * @param \Magein\Payment\Lib\Data\PayData $payData
     * @return mixed
     */
    abstract function unify(PayData $payData);

    /**
     * 查询支付订单
     * @param string|\Magein\Payment\Models\PayOrder $pay_no
     * @return mixed
     */
    abstract function queryByPayNo($pay_no);

    /**
     * 异步通知处理
     * @param $data
     * @param \Magein\Payment\Models\PayOrder $payOrder
     * @return \Magein\Payment\Lib\Data\PayNotifyData|null
     */
    abstract function notify($data, PayOrder $payOrder): ?PayNotifyData;

    /**
     * 异步通知成功后相应平台的值
     * @return string|null
     */
    abstract function notifySuccess(): ?string;

    /**
     * Pay constructor.
     * @param string|int $key
     */
    public function __construct($key = '')
    {
        if ($key) {
            $this->setAccount($key);
        } else {
            $this->payAccount = new PayAccountData();
        }
    }

    /**
     * 平台编号
     * @return int|string
     */
    public function getPlatformId()
    {
        $class_name = class_basename($this);

        return $class_name;
    }

    /**
     *  平台名称
     * @return int|string
     */
    public function getPlatformName()
    {

    }

    /**
     * 获取异步通知地址
     * @param $pay_no
     * @return string
     */
    public function getNotifyUrl($pay_no): string
    {
        return trim(config('app.url'), '/') . '/api/pay/notify/' . $pay_no;
    }

    /**
     * 获取支付的平台的配置文件
     * @param null $key
     * @return array|string
     */
    public function getPlatformConfig($key = null)
    {
        $platform_name = $this->getPlatformId();

        $config = config("pay.$platform_name") ?: [];

        if ($config && $key !== null) {
            return $config[$key];
        }

        return $config;
    }

    /**
     * 设置一个支付账户，这里目前使用随机值
     * 后续可以根据需求变动，修改代码在这个方法中实现
     * 如：平均分配、限定额度等，搭配redis
     * @param $key
     * @return array|mixed
     */
    public function setAccount($key = null)
    {
        $config = $this->getPlatformConfig();
        $accounts = $config['account'];
        // 没有指定商户号的时候从use配置中随机取出一个，当某个账号异常的时候，直接从use中注释掉即可，但是不影响查询功能
        if (!$key) {
            $use = $config['use'];
            if ($use) {
                $key = $use[rand(0, count($use) - 1)];
            } else {
                $key = array_rand($accounts);
            }
        }
        $account = $accounts[$key];

        $payAccount = new PayAccountData();
        $payAccount->setMode($account['mode'] ?? 'dev');
        $payAccount->setAppid($account['appid'] ?? '');
        $payAccount->setMerchantId($account['merchant_id'] ?? '');
        $payAccount->setKey($account['key'] ?? '');
        $payAccount->setSecret($account['secret'] ?? '');
        $payAccount->setTerminalId($account['terminal_id'] ?? '');

        // 平台公钥、商户私钥（杉德支付，使用的是路径）
        $payAccount->setPlatformPublicKey($account['platform_public_key'] ?? '');
        $payAccount->setMerchantPrivateKey($account['merchant_private_key'] ?? '');

        // 支付宝的配置
        $payAccount->setEncryptKey($account['encrypt_key'] ?? '');

        $others = $account['others'] ?? [];
        if (is_array($others)) {
            $payAccount->setOthers($others);
        }
        $this->payAccount = $payAccount;

        return $account;
    }

    /**
     * @param \Magein\Payment\Lib\Data\PayData $payData
     * @return string
     */
    public function createPayOrder(PayData $payData): string
    {
        while (true) {
            try {
                $model = new PayOrder();
                $model->pay_no = $payData->getPayNo() ?: date('YmdHis') . rand(10000000, 99999999);
                $model->user_id = $payData->getUserId();
                $model->order_no = $payData->getOrderNo();
                $model->platform = $payData->getPlatform();
                $model->trade_no = $payData->getPlatformPayType();
                $model->appid = $payData->getAppId();
                $model->merchant_id = $payData->getMerchantId();
                $model->total_amount = $payData->getTotalAmount();
                $model->scene = $payData->getScene();
                if ($model->save()) {
                    $pay_no = $model->pay_no;
                    break;
                }
            } catch (\Exception $exception) {

            }
        }

        return $pay_no;
    }
}
