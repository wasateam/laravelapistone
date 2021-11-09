<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopShipAreaSettingAreaSectionTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_ship_area_setting_area_section', function (Blueprint $table) {
      $table->string('shop_ship_area_setting_id');
      $table->string('area_section_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_ship_area_setting_area_section');
  }
}
