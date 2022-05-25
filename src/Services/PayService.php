<?php

namespace Magein\Payment\Services;

use Illuminate\Support\Facades\Request;
use Magein\Common\BaseService;
use Magein\Payment\Lib\Data\PayData;
use Magein\Payment\Lib\PayFactory;

class PayService extends BaseService
{
    protected function payData(Request $request)
    {
        $platform_id = $request::input('platform_id', '');
        $total_amount = floatval($request::input('total_amount'));
        $order_no = $request::input('order_no');
        $user_id = $request::input('user_id');
        $scene = $request::input('scene', 1);
        $subject = $request::input('subject', '支付订单');
        $complete_url = $request::input('complete_url', '');

        $payData = new PayData();
        $payData->setUserId($user_id);
        $payData->setSubject($subject);
        $payData->setTotalAmount($total_amount);
        $payData->setOrderNo($order_no);
        $payData->setScene($scene);
        $payData->setCompleteUrl($complete_url);
        $payData->setPlatformId($platform_id);

        return $payData;
    }

    /**
     * @param PayData|Request $request
     */
    public function unify($request)
    {
        if ($request instanceof Request) {
            $payData = $this->payData($request);
        } elseif ($request instanceof PayData) {
            $payData = $request;
        } else {
            return $this->error('参数错误');
        }

        $platform = PayFactory::platform($payData->getPlatformId());


        return true;
    }

    public function notify($pay_no)
    {
        return true;
    }

    public function complete($pay_no)
    {
        return true;
    }

    public function query($pay_no): array
    {
        return [];
    }

    public function refund(Request $request): array
    {
        return [];
    }

    public function refundQuery($refund_no): array
    {
        return [];
    }

    public function transfer(Request $request)
    {
        return [];
    }
}