<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrderShopProductSpecShopProductSpecSettingTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_order_shop_product_spec_shop_product_spec_setting', function (Blueprint $table) {
      $table->integer('shop_order_shop_product_spec_id');
      $table->integer('shop_order_shop_product_spec_setting_item_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_order_shop_product_spec_shop_product_spec_setting');
  }
}
