<?php

namespace Magein\Payment\Lib\Data;

class TransferAccountData
{
    /**
     * @var string
     */
    private string $transfer_no = '';

    /**
     * 收款方账号
     * @var string
     */
    private string $account = '';

    /**
     * 收款方名称
     * @var string
     */
    private string $name = '';

    /**
     * 此次转账金额
     * @var float|int
     */
    private float $amount = 0;

    /**
     * 转账标题
     * @var string
     */
    private string $subject = '';

    /**
     * 此次转账备注
     * @var string
     */
    private string $remark = '';

    /**
     * @return string
     */
    public function getTransferNo(): string
    {
        return $this->transfer_no;
    }

    /**
     * @param string $transfer_no
     */
    public function setTransferNo(string $transfer_no): void
    {
        $this->transfer_no = $transfer_no;
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @param string $account
     */
    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float|int $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = floatval($amount);
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }
}
