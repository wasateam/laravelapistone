<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductSpecShopProductSpecSettingItemTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_product_spec_shop_product_spec_setting_item', function (Blueprint $table) {
      $table->integer('shop_product_spec_id');
      $table->integer('shop_product_spec_setting_item_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_product_spec_shop_product_spec_setting_item');
  }
}
