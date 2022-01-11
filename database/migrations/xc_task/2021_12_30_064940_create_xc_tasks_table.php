<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcTasksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('xc_tasks', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('xc_work_type_id')->nullable();
      $table->string('xc_project_id')->nullable();
      $table->string('name')->nullable();
      $table->string('creator_id')->nullable();
      $table->string('taker_id')->nullable();
      $table->timestamp('start_at')->nullable();
      $table->timestamp('reviewed_at')->nullable();
      $table->string('status')->nullable();
      $table->string('hour')->nullable();
      $table->string('finish_hour')->nullable();
      $table->text('creator_remark')->nullable();
      $table->text('taker_remark')->nullable();
      $table->boolean('is_adjust')->nullable();
      $table->boolean('is_rd')->nullable();
      $table->boolean('is_not_complete')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('xc_tasks');
  }
}
