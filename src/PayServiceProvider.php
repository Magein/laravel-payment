<?php

namespace Magein\Payment;

use Illuminate\Support\ServiceProvider;

/**
 * 参考地址 https://learnku.com/laravel/t/35930
 */
class PayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (is_file(__DIR__ . '/Common.php')) {
            require_once __DIR__ . '/Common.php';
        }

        $this->publishes([
            __DIR__ . '/Config.php' => config_path('pay.php'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations/');
        $this->loadRoutesFrom(__DIR__ . '/Router.php');
    }
}
