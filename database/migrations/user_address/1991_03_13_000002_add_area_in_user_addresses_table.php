<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaInUserAddressesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_addresses', function (Blueprint $table) {
      $table->integer('area_id')->nullable();
      $table->integer('area_section_id')->nullable();
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
      $table->dropColumn('area_id');
      $table->dropColumn('area_section_id');
    });
  }
}
