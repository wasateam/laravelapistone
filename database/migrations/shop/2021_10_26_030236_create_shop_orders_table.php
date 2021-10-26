<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('uuid')->nullable()->unique();
            $table->string('type')->nullable(); // 訂單類型
            $table->string('no')->nullable(); // 訂單編號
            $table->boolean('selected')->nullable()->default(false); // 選取狀態
            $table->datetime('order_time')->nullable(); // 訂購日期
            // 訂購者是否有關聯到其他資料表
            $table->string('receiver')->nullable(); // 收件者
            $table->string('receiver_tel')->nullable(); // 收件人電話
            $table->string('receiver_address')->nullable(); // 收件人地址
            $table->text('receive_remark')->nullable(); // 收件備註
            $table->string('package_methods')->nullable(); // 包裝方式
            $table->string('order_status')->nullable(); // 訂單狀態
            $table->string('logistics_methods')->nullable(); // 物流方式
            $table->time('delivery_time')->nullable(); // 配送時段
            $table->text('delivery_remark')->nullable(); // 配送備註
            $table->string('shipment_status')->nullable(); // 出貨狀態
            $table->date('shipment_date')->nullable(); // 出貨日期
            $table->text('customer_service')->nullable(); // 客服備註
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_orders');
    }
}
