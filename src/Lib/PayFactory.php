<?php


namespace Magein\Payment\Lib;

use App\Payment\Lib\Platform\WxPay;

class PayFactory
{
    public static function platform($platform_id, bool $set_account = false)
    {
        if (empty($platform_id)) {
            if (isMicroMessenger()) {

            }
        }
    }

    public static function getPlatformName($platform_id = null)
    {

    }

    public static function getMerchantName($merchant_id = null)
    {

    }
}
