<?php


namespace Magein\Payment\Lib\Data;


class PayNotifyData
{
    /**
     * 订单交易编号，传递给第三方平台的，不能为空且唯一
     * @var string
     */
    private string $pay_no = '';

    /**
     * 第三方交易平台生成的订单编号
     * @var string
     */
    private string $trade_no = '';

    /**
     * 结果
     * @var int
     */
    private int $result = 0;

    /**
     * 原因
     * @var string
     */
    private string $reason = '';

    /**
     * 通知时间
     * @var string
     */
    private string $notify_date = '';

    /**
     * 回调的原始参数
     * @var string
     */
    private string $notify_data = '';

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
        $this->pay_no = $pay_no;
    }

    public function getTradeNo(): string
    {
        return $this->trade_no ?: '';
    }

    /**
     * @param string $trade_no
     */
    public function setTradeNo(string $trade_no): void
    {
        $this->trade_no = $trade_no;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @param int $result
     */
    public function setResult(int $result): void
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getNotifyDate(): string
    {
        return $this->notify_date ?: now();
    }

    /**
     * @param string $notify_date
     */
    public function setNotifyDate(string $notify_date = ''): void
    {
        $this->notify_date = $notify_date ?: now();
    }

    /**
     * @return string
     */
    public function getNotifyPayType(): string
    {
        return $this->notify_pay_type;
    }

    /**
     * @param string $notify_pay_type
     */
    public function setNotifyPayType(string $notify_pay_type): void
    {
        $this->notify_pay_type = $notify_pay_type;
    }

    /**
     * @return string
     */
    public function getNotifyData(): string
    {
        return $this->notify_data;
    }

    /**
     * @param string|array $notify_data
     */
    public function setNotifyData($notify_data): void
    {
        if (is_array($notify_data)) {
            $notify_data = json_encode($notify_data, true);
        }

        $this->notify_data = $notify_data;
    }

    /**
     * @return string
     */
    public function getNoticeUrl(): string
    {
        return $this->notice_url;
    }

    /**
     * @param string $notice_url
     */
    public function setNoticeUrl(string $notice_url): void
    {
        $this->notice_url = $notice_url;
    }


    public function success()
    {
        $this->result = 1;
    }

    public function fail($reason = '')
    {
        $this->result = -1;
        if ($reason) {
            $this->reason = $reason;
        }
    }

}
