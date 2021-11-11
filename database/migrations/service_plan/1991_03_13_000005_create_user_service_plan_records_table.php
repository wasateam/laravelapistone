<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserServicePlanRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_service_plan_records', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('user_id')->nullable();
      $table->string('service_plan_id')->nullable();
      $table->string('service_plan_item_id')->nullable();
      $table->string('user_service_plan_id')->nullable();
      $table->string('user_service_plan_item_id')->nullable();
      $table->string('admin_id')->nullable();
      $table->string('count')->nullable();
      $table->string('remark')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_service_plan_records');
  }
}
