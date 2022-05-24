<?php

namespace Magein\Payment\Providers;

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
        if (is_file(__DIR__ . '/../Common.php')) {
            require_once __DIR__ . '/../Common.php';
        }

        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 加载命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModel::class,
                MakeModelProperty::class
            ]);
        }
    }
}
