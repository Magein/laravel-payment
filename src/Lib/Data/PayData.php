<?php

namespace Magein\Payment\Lib\Data;

class  PayData
{
    /**
     * 订单交易编号，传递给第三方平台的，不能为空且唯一
     * @var string
     */
    private string $pay_no = '';

    /**
     * 订单编号，真实的订单编号
     * @var string
     */
    private string $order_no = '';

    /**
     * 使用场景
     * @var integer
     */
    private int $scene = 1;

    /**
     * 交易金额
     * @var float
     */
    private float $total_amount = 0;

    /**
     * 订单标题。
     * @var string
     */
    private string $subject = '';

    /**
     * 订单附加信息。
     * 如果请求时传递了该参数，将在异步通知、对账单中原样返回，同时会在商户和用户的pc账单详情中作为交易描述展示
     * @var string
     */
    private string $body = '';

    /**
     * 支付完成跳转的页面
     * @var string
     */
    private string $complete_url = '';

    /**
     * 交易平台
     * @var string|int
     */
    private $platform_id = '';

    /**
     * 应用ID
     * @var string
     */
    private string $appid = '';

    /**
     * 商户id
     * @var string
     */
    private string $merchant_id = '';

    /**
     * 交易方式 wap、jsapi、app等
     * @var string
     */
    private $trade_type = '';

    /**
     * 用户id
     * @var string|int
     */
    private $user_id = '';

    /**
     * 用户授权id
     * @var string|integer
     */
    private string $open_id = '';

    /**
     * @return string
     */
    public function getPayNo(): string
    {
        return $this->pay_no;
    }

    /**
     * @param string $pay_no
     */
    public function setPayNo(string $pay_no): void
    {
        if (strlen($pay_no) > 22) {
            $pay_no = substr($pay_no, 0, 22);
        }
        $this->pay_no = $pay_no;
    }

    /**
     * @return string
     */
    public function getOrderNo(): string
    {
        return $this->order_no;
    }

    /**
     * @param string $order_no
     */
    public function setOrderNo(string $order_no): void
    {
        $this->order_no = $order_no;
    }

    /**
     * @return string
     */
    public function getTotalAmount(): string
    {
        return $this->total_amount;
    }

    /**
     * @param mixed $total_amount
     */
    public function setTotalAmount($total_amount): void
    {
        $this->total_amount = floatval($total_amount);
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject ?: '';
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getCompleteUrl(): string
    {
        return $this->complete_url;
    }

    /**
     * @param string $complete_url
     */
    public function setCompleteUrl(string $complete_url): void
    {
        $this->complete_url = $complete_url ?: '';
    }

    /**
     * @return int
     */
    public function getScene(): int
    {
        return $this->scene;
    }

    /**
     * @param int $scene
     */
    public function setScene(int $scene): void
    {
        $this->scene = $scene;
    }

    /**
     * @return string
     */
    public function getPlatformId(): string
    {
        return $this->platform_id;
    }

    /**
     * @param string|int $platform_id
     */
    public function setPlatformId($platform_id): void
    {
        $this->platform_id = (string)$platform_id;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appid;
    }

    /**
     * @param string $appid
     */
    public function setAppId(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchant_id ?: '';
    }

    /**
     * @param string|null $merchant_id
     */
    public function setMerchantId(?string $merchant_id): void
    {
        $this->merchant_id = $merchant_id ?: '';
    }

    /**
     * @return string
     */
    public function getTradeType(): string
    {
        return $this->trade_type;
    }

    /**
     * @param string $trade_type
     */
    public function setTradeType(string $trade_type): void
    {
        $this->trade_type = $trade_type;
    }

    /**
     * @return string
     */
    public function getOpenId(): string
    {
        return $this->open_id;
    }

    /**
     * @param string $open_id
     */
    public function setOpenId(string $open_id): void
    {
        $this->open_id = $open_id;
    }

    /**
     * @return integer
     */
    public function getUserId(): int
    {
        return intval($this->user_id);
    }

    /**
     * @param string|integer $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = intval($user_id);
    }
}
