<?php

/**
 * 前端支付请求接口
 */

use Illuminate\Support\Facades\Route;
use Magein\Payment\Controllers\Pay;

Route::prefix('api')->group(function () {
    Route::get('pay/unify', [Pay::class, 'unify']);
    Route::get('pay/notify', [Pay::class, 'notify']);
    Route::get('pay/complete', [Pay::class, 'complete']);
    Route::get('pay/query', [Pay::class, 'query']);
    Route::get('pay/refund', [Pay::class, 'refund']);
    Route::get('pay/refundQuery', [Pay::class, 'refundQuery']);
    Route::get('pay/transfer', [Pay::class, 'transfer']);
});
