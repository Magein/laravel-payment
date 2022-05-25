<?php


namespace App\Payment\Lib\Platform;

use Magein\Payment\Lib\Data\PayData;
use Magein\Payment\Lib\Data\PayNotifyData;
use Magein\Payment\Lib\Pay;
use Magein\Payment\Models\PayOrder;

class WxPay extends Pay
{

    public function getPlatformId()
    {

    }

    public function getPlatformName()
    {

    }

    public function unify(PayData $payData)
    {

    }

    public function queryByPayNo($pay_no)
    {

    }

    public function notify($data, PayOrder $payOrder): ?PayNotifyData
    {

    }

    public function notifySuccess(): ?string
    {

    }
}
