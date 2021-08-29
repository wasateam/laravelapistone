<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransportationInfoInServiceStoresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->text('transportation_Info')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->dropColumn('transportation_Info');
    });
  }
}
