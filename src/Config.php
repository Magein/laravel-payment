<?php

!defined('PAY_MODE_DEVELOP') && define('PAY_MODE_DEVELOP', 'dev');
!defined('PAY_MODE_PRODUCT') && define('PAY_MODE_PRODUCT', 'pro');

return [

    'default' => [
        // 默认使用的平台
        'platform' => \App\Payment\Lib\Platform\AliPay::class,
        // 使用账户的模式 1=指定账户（从use中取第一个）  2=随机（从use中随机取出一个） 3=顺序
        'method' => 1
    ],

    'ali' => [
        'name' => '支付宝',
        'platform' => \App\Payment\Lib\Platform\AliPay::class,
        'use' => [
            '12234',
            '45223'
        ],
        'accounts' => [
            '12234' => [
                'mode' => PAY_MODE_PRODUCT,
                'appid' => '12234',
                'encrypt_key' => '',
                'merchant_private_key' => '',
                'platform_public_key' => '',
            ],
            '45223' => [
                'mode' => PAY_MODE_PRODUCT,
                'appid' => '12234',
                'encrypt_key' => '',
                'merchant_private_key' => '',
                'platform_public_key' => '',
                'others' => [
                    // 支付宝公钥证书文件路径
                    'cert_public' => '/Ali/alipayCertPublicKey_RSA2.crt',
                    // 支付宝根证书文件路径
                    'cert_root' => '/Ali/alipayRootCert.crt',
                    // 应用公钥证书文件路径
                    'cert_merchant' => '/Ali/appCertPublicKey_2021003128645029.crt',
                ]
            ],
        ],
    ],
    'wx' => [
        'name' => '微信',
        'platform' => \App\Payment\Lib\Platform\WxPay::class,
        'use' => [
            '1323241'
        ],
        'accounts' => [
            '1323241' => [
                'mode' => PAY_MODE_PRODUCT,
                'appid' => 'wx12313',
                'secret' => '123132',
                'merchant_id' => '123131',
                'key' => '312313',
            ],
        ],
    ],
];
