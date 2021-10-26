<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_bills', function (Blueprint $table) {
            $table->id();
            $table->string('')->nullable(); // 發票號碼
            $table->string('')->nullable(); // 發票狀態
            $table->string('')->nullable(); // 發票形式
            $table->string('')->nullable(); // 載具編號
            $table->string('')->nullable(); // 統一編號
            $table->string('')->nullable(); // 抬頭
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
        Schema::dropIfExists('shop_bills');
    }
}
