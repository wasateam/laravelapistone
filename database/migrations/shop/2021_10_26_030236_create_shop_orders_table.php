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
    /**
     * 目前尚未分類 cms、webapi 欄位
     *
     *
     *
     */
    Schema::create('shop_orders', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid('uuid')->nullable()->unique();
      $table->integer('created_user_id')->nulllable();
      $table->integer('updated_user_id')->nulllable();
      // 訂單類型 （種類）
      $table->string('type')->nullable();
      // 訂單編號
      $table->string('no')->nullable();
      //訂購者
      $table->text('orderer')->nullable();
      $table->text('orderer_tel')->nullable();
      $table->date('orderer_birthday')->nullable();
      $table->text('orderer_email')->nullable();
      $table->text('orderer_gender')->nullable();
      // 收件者
      $table->text('receiver')->nullable();
      $table->text('receiver_tel')->nullable();
      $table->text('receiver_email')->nullable();
      $table->text('receiver_gender')->nullable();
      $table->date('receiver_birthday')->nullable();
      // 收件地址
      $table->integer('user_address_id')->nullable();
      // 收件備註
      $table->text('receive_remark')->nullable();
      // 包裝方式
      $table->integer('package_method_id')->nullable();
      // 訂單狀態
      $table->integer('status')->nullable();
      // 訂單備註狀態
      $table->text('status_remark')->nullable();
      // 物流方式
      $table->integer('deliver_way_id')->nullable();
      $table->time('delivery_time')->nullable();
      $table->text('delivery_remark')->nullable();
      // 客服備註
      $table->text('customer_service_remark')->nullable();
      // Area
      $table->integer('area_id')->nullable();
      // AreaSection
      $table->integer('area_section_id')->nullable();
      // 付款狀態關連到 付款資訊
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
