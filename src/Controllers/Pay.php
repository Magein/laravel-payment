<?php

namespace Magein\Payment\Controllers;

use Illuminate\Support\Facades\Request;
use Magein\Payment\Services\PayService;

class Pay
{
    // 统一下单
    public function unify(Request $request)
    {
        return PayService::instance()->unify($request);
    }

    // 异步通知
    public function notify(Request $request)
    {
        $pay_no = $request::route('pay_no');

        return PayService::instance()->notify($pay_no);
    }

    // 支付完成后的跳转
    public function complete(Request $request)
    {
        $pay_no = $request::route('pay_no');

        return PayService::instance()->complete($pay_no);
    }

    // 支付查询
    public function query(Request $request)
    {
        $pay_no = $request::input('pay_no');

        return PayService::instance()->query($pay_no);
    }

    // 退款
    public function refund(Request $request)
    {
        return PayService::instance()->refund($request);
    }

    // 退款查询
    public function refundQuery(Request $request)
    {
        $refund_no = $request::input('refund_no');

        return PayService::instance()->refundQuery($refund_no);
    }

    // 转账
    public function transfer(Request $request)
    {
        return PayService::instance()->transfer($request);
    }
}