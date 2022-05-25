<?php


namespace Magein\Payment\Lib\Data;


class PayAccount
{
    /**
     * 账号所使用的的模式，dev测试账号  pro 生产环境账号
     * @var string
     */
    private string $mode = '';

    /**
     * 应用id 每个支付平台都会分配一个应用id
     * @var string
     */
    private string $appid = '';

    /**
     * key 微信、银联持有
     * @var string
     */
    private string $key = '';

    /**
     * @var string
     */
    private string $secret = '';

    /**
     * 商户id 微信、银联持有
     * @var string
     */
    private string $merchant_id = '';

    /**
     * 设备id 微信、银联持有
     * @var string
     */
    private string $terminal_id = '';

    /**
     * 支付宝使用RSA2加密的秘钥
     * @var string
     */
    private string $encrypt_key = '';

    /**
     * 商户私钥
     * 1. 支付宝使用RSA2加密的商户私钥
     * 2. 杉德使用的商户私钥的路径
     * @var string
     */
    private string $merchant_private_key = '';

    /**
     * 平台公钥，异步通知解签使用
     * 支付宝、杉德支付的平台公钥
     * 1. 支付宝需要在平台查看，是一串字符串
     * 2. 杉德的公钥是路径
     * @var string
     */
    private string $platform_public_key = '';

    /**
     * 其他附加参数
     * @var array
     */
    private array $others = [];

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * @param string $appid
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    /**
     * @param string $merchant_id
     */
    public function setMerchantId(string $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * @return string
     */
    public function getTerminalId(): string
    {
        return $this->terminal_id;
    }

    /**
     * @param string $terminal_id
     */
    public function setTerminalId(string $terminal_id): void
    {
        $this->terminal_id = $terminal_id;
    }

    /**
     * @return string
     */
    public function getEncryptKey(): string
    {
        return $this->encrypt_key;
    }

    /**
     * @param string $encrypt_key
     */
    public function setEncryptKey(string $encrypt_key): void
    {
        $this->encrypt_key = $encrypt_key;
    }

    /**
     * @return string
     */
    public function getMerchantPrivateKey(): string
    {
        return $this->merchant_private_key;
    }

    /**
     * @param string $merchant_private_key
     */
    public function setMerchantPrivateKey(string $merchant_private_key): void
    {
        $this->merchant_private_key = $merchant_private_key;
    }

    /**
     * @return string
     */
    public function getPlatformPublicKey(): string
    {
        return $this->platform_public_key;
    }

    /**
     * @param string $platform_public_key
     */
    public function setPlatformPublicKey(string $platform_public_key): void
    {
        $this->platform_public_key = $platform_public_key;
    }

    /**
     * @return array
     */
    public function getOthers(): array
    {
        return $this->others;
    }

    /**
     * @param array $others
     */
    public function setOthers(array $others): void
    {
        $this->others = $others;
    }
}
