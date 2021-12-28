<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeInUserDevicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_devices', function (Blueprint $table) {
      $table->string('type')->nullable();
      $table->boolean('is_diy')->nullable()->default(0);
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
      $table->dropColumn('type');
      $table->dropColumn('is_diy');
    });
  }
}
