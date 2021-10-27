<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopPayInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_pay_infos', function (Blueprint $table) {
            $table->id();
            // 付款方式
            // 付款狀態
            // 優惠活動/方式/金額
            // 運費
            // 商品總金額
            // 訂單金額
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_pay_infos');
    }
}
