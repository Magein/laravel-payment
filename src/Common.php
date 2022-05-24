<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('isMicroMessenger')) {
    /**
     * 是否是微信浏览器
     * @return bool
     */
    function isMicroMessenger(): bool
    {
        if (strpos(Request::header('user-agent'), 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getAmount')) {
    /**
     * 处理金额
     * @param string|integer|float $amount 金额
     * @param int $len 小数点后面保留的长度
     * @return float
     */
    function getAmount($amount, int $len = 2): float
    {
        $amount = (string)$amount;
        $position = strpos($amount, '.');
        if (false === $position) {
            return floatval($amount);
        }

        return floatval(substr($amount, 0, $position + $len + 1));
    }
}

