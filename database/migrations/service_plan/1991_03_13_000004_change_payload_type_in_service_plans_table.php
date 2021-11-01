<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePayloadTypeInServicePlansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_plans', function (Blueprint $table) {
      $table->longText('payload')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('service_plans', function (Blueprint $table) {
      $table->string('payload')->nullable()->change();
    });
  }
}
