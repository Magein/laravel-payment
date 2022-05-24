<?php

namespace Magein\Payment\Models;

use Magein\Common\BaseModel;

/**
 * @property integer $user_id
 * @property string $pay_no
 * @property string $order_no
 * @property string $trade_no
 * @property string $scene
 * @property integer $appid
 * @property integer $merchant_id
 * @property string $platform
 * @property string $trade_type
 * @property float $total_amount
 * @property float $buyer_amount
 * @property float $buyer_id
 * @property string $result
 * @property string $reason
 * @property string $notify_date
 * @property string $notify_data
 * @method static PayOrder|null _payNo($pay_no);
 * @method static \Illuminate\Database\Eloquent\Collection|null __payNo($pay_no);
 * @method static \Illuminate\Pagination\LengthAwarePaginator|null ___payNo($pay_no);
 * @method static PayOrder|null _orderNo($order_no);
 * @method static \Illuminate\Database\Eloquent\Collection|null __orderNo($order_no);
 * @method static \Illuminate\Pagination\LengthAwarePaginator|null ___orderNo($order_no);
 * @method static PayOrder|null _tradeNo($trade_no);
 * @method static \Illuminate\Database\Eloquent\Collection|null __tradeNo($trade_no);
 * @method static \Illuminate\Pagination\LengthAwarePaginator|null ___tradeNo($trade_no);
 * @method static PayOrder|null _appId($app_id);
 * @method static \Illuminate\Database\Eloquent\Collection|null __appId($app_id);
 * @method static \Illuminate\Pagination\LengthAwarePaginator|null ___appId($app_id);
 */
class PayOrder extends BaseModel
{
    public $casts = [
        'post_data' => 'json'
    ];

    /**
     * 设置支付订单编号
     * @param $value
     */
    public function setPayNoAttribute($value)
    {
        if (strlen($value) > 22) {
            $value = substr($value, 0, 22);
        }

        $this->attributes['pay_no'] = $value;
    }
}
