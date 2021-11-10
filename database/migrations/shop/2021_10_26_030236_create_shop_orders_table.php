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
      $table->text('receive_address')->nullable();
      // 收件備註
      $table->text('receive_remark')->nullable();
      // 包裝方式
      $table->text('package_way')->nullable();
      // 訂單狀態
      $table->integer('status')->nullable();
      // 訂單備註狀態
      $table->text('status_remark')->nullable();
      //收穫方式
      $table->text('receive_way')->nullable();
      // 物流方式
      $table->integer('shop_ship_area_setting_id')->nullable();
      $table->text('ship_way')->nullable();
      $table->datetime('delivery_date')->nullable();
      $table->integer('shop_ship_time_setting_id')->nullable();
      $table->time('ship_start_time')->nullable();
      $table->time('ship_end_time')->nullable();
      $table->text('ship_remark')->nullable();
      $table->datetime('ship_date')->nullable();
      $table->integer('ship_status')->default(0);
      // 客服備註
      $table->text('customer_service_remark')->nullable();
      // Area
      $table->integer('area_id')->nullable();
      // AreaSection
      $table->integer('area_section_id')->nullable();
      //付款資訊
      $table->text('pay_type')->nullable();
      $table->text('pay_status')->nullable();
      $table->longText('discounts')->nullable();
      $table->integer('freight')->nullable();
      $table->integer('products_price')->nullable();
      $table->integer('order_price')->nullable();
      //發票資訊
      $table->text('receipt_number')->nullable();
      $table->text('receipt_status')->nullable();
      $table->text('receipt_type')->nullable();
      $table->text('receipt_carrier_number')->nullable();
      $table->text('receipt_tax')->nullable();
      $table->text('receipt_title')->nullable();
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
