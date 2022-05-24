<?php

namespace Magein\Payment\Lib\Data;

class  PayData
{
    /**
     * 订单交易编号，传递给第三方平台的，不能为空且唯一
     * @var string
     */
    private $pay_no = '';

    /**
     * 订单编号，真实的订单编号
     * @var string
     */
    private $order_no = '';

    /**
     * 使用场景
     * @var integer
     */
    private $scene = 1;

    /**
     * 交易金额
     * @var string
     */
    private $total_amount = '';

    /**
     * 订单标题。
     * @var string
     */
    private $subject = '';

    /**
     * 订单附加信息。
     * 如果请求时传递了该参数，将在异步通知、对账单中原样返回，同时会在商户和用户的pc账单详情中作为交易描述展示
     * @var string
     */
    private $body = '';

    /**
     * 用户付款中途退出返回商户网站的地址
     * @var string
     */
    private $quit_url = '';

    /**
     * 支付完成跳转的页面
     * @var string
     */
    private $return_url = '';

    /**
     * 通知url
     * @var string
     */
    private $notice_url = '';

    /**
     * 交易平台
     * @var string
     */
    private $platform = '';

    /**
     * 应用ID
     * @var string
     */
    private $appid = '';

    /**
     * 商户id
     * @var string
     */
    private $merchant_id = '';

    /**
     * 平台的交易方式，app、H5等
     * @var string
     */
    private $platform_pay_type = '';

    /**
     * 微信的open_id
     * @var string
     */
    private $open_id = '';

    /**
     * 用户id
     * @var string|integer
     */
    private $user_id = '';

    /**
     * @var array
     */
    private $post_data = [];

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
    public function getQuitUrl(): string
    {
        return $this->quit_url;
    }

    /**
     * @param string|null $quit_url
     */
    public function setQuitUrl(?string $quit_url): void
    {
        $this->quit_url = $quit_url ?: '';
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->return_url;
    }

    /**
     * @param string|null $return_url
     */
    public function setReturnUrl(?string $return_url): void
    {
        $this->return_url = $return_url ?: '';
    }

    /**
     * @return string
     */
    public function getNoticeUrl(): string
    {
        return $this->notice_url;
    }

    /**
     * @param string|null $notice_url
     */
    public function setNoticeUrl(?string $notice_url): void
    {
        $this->notice_url = $notice_url;
    }

    public function setUrl(?string $notice_url, $pay_no = '')
    {
        if ($notice_url) {
            // 通知地址，保存的是原始参数
            $this->notice_url = $notice_url;
            $concat = function ($pay_type) use ($pay_no) {
                return config('app.url') . "api/pay/redirect/$pay_type/$pay_no";
            };
            // 同步跳转是跳转到项目的地址，然后分发
            $this->return_url = $concat('success');
            $this->quit_url = $concat('quit');
        }
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
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     */
    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
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
    public function getPlatformPayType(): string
    {
        return $this->platform_pay_type;
    }

    /**
     * @param string $platform_pay_type
     */
    public function setPlatformPayType(string $platform_pay_type): void
    {
        $this->platform_pay_type = $platform_pay_type;
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

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->post_data ?: [];
    }

    /**
     * @param array $data
     */
    public function setPostData(array $data): void
    {
        $this->post_data = $data;
    }
}
