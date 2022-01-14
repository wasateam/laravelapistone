<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSerialNumberInUserDevicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_devices', function (Blueprint $table) {
      $table->renameColumn('serial_number', 'model_number');
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
      $table->renameColumn('model_number', 'serial_number');
    });
  }
}
