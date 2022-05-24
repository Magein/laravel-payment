<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_orders', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->id();
            $table->char('pay_no', 22)->comment('支付编号 请求第三方支付编号，唯一')->unique();
            $table->char('order_no', 32)->comment('平台订单编号')->index();
            $table->string('trade_no')->comment('交易编号 第三方返回的的唯一标记')->default('');
            $table->string('user_id')->comment('用户ID');
            $table->tinyInteger('scene')->comment('场景 1 支付订单 order 2 充值 recharge')->default(1);
            $table->string('platform')->comment('交易平台');
            $table->string('appid')->comment('平台ID');
            $table->string('merchant_id')->comment('商户编号');
            $table->string('trade_type')->comment('平台交易方式 支付宝的h5、面对面、app，微信的jsapi、h5等');
            $table->integer('total_amount')->comment('交易总金额 单位分')->unsigned();
            $table->integer('buyer_amount')->comment('买家实付 单位分')->unsigned();
            $table->string('buyer_id')->comment('买家标识');
            $table->tinyInteger('result')->comment('交易结果 0 等待中 pending 1 成功 success -1 失败 fail')->default(0);
            $table->string('reason')->comment('交易失败原因')->default('');
            $table->timestamp('notify_date')->comment('异步通知时间')->nullable();
            $table->text('notify_data')->comment('异步通知参数')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_orders');
    }
}
