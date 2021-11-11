<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodMonthInServicePlansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_plans', function (Blueprint $table) {
      $table->string('period_month')->nullable();
      $table->string('total_price')->nullable();
      $table->string('annual_price')->nullable();
      $table->string('monthly_price')->nullable();
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
      $table->dropColumn('period_month');
      $table->dropColumn('total_price');
      $table->dropColumn('annual_price');
      $table->dropColumn('monthly_price');
    });
  }
}
