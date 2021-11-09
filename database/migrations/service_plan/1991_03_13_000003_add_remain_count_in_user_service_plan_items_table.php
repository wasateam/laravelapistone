<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainCountInUserServicePlanItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('user_service_plan_items', function (Blueprint $table) {
      $table->integer('total_count')->nullable();
      $table->integer('remain_count')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('user_service_plan_items', function (Blueprint $table) {
      //
    });
  }
}
