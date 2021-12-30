<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandInUserDevicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_devices', function (Blueprint $table) {
      $table->string('brand')->nullable();
      $table->string('product_number')->nullable();
      $table->string('country_code')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('user_devices', function (Blueprint $table) {
      $table->dropColumn('brand');
      $table->dropColumn('product_number');
      $table->dropColumn('country_code');
    });
  }
}
