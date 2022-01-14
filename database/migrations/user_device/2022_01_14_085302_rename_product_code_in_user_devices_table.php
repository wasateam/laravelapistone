<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductCodeInUserDevicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_devices', function (Blueprint $table) {
      $table->renameColumn('product_code', 'serial_number');
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
      $table->renameColumn('serial_number', 'product_code');
    });
  }
}
