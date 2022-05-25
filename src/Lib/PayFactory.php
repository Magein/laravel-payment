<?php


namespace Magein\Payment\Lib;

use Magein\Payment\Lib\Platform\AliPay;
use Magein\Payment\Lib\Platform\WxPay;

class PayFactory
{
    public static function platform($platform_id = '', bool $set_account = false)
    {
        $pay_config = config('pay.default');
        if (empty($platform_id)) {
            if (isMicroMessenger()) {
                $platform = new WxPay();
            } else {
                $platform = new AliPay();
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
