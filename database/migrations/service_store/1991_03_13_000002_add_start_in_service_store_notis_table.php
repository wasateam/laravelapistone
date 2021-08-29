<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartInServiceStoreNotisTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_store_notis', function (Blueprint $table) {
      $table->timestamp('start')->nullable();
      $table->timestamp('end')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('service_store_notis', function (Blueprint $table) {
      $table->dropColumn('start');
      $table->dropColumn('end');
    });
  }
}
