<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_receipts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // 發票號碼
            $table->string('number')->nullable();
            // 發票狀態
            $table->string('status')->nullable(); 
            // 發票形式
            $table->string('type')->nullable(); 
            // 載具編號
            $table->string('carrier_number')->nullable();
            // 統一編號
            $table->string('tax_id_number')->nullable(); 
            // 抬頭
            $table->string('company')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_receipts');
    }
}
