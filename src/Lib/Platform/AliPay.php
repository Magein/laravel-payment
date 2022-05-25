<?php

namespace App\Payment\Lib\Platform;

use Magein\Payment\Lib\Data\PayData;
use Magein\Payment\Lib\Data\PayNotifyData;
use Magein\Payment\Lib\Pay;
use Magein\Payment\Models\PayOrder;

class AliPay extends Pay
{

    public function getPlatformId()
    {
        return 'ali';
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
