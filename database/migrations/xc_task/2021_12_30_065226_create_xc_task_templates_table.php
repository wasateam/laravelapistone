<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcTaskTemplatesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('xc_task_templates', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('xc_work_type_id')->nullable();
      $table->string('name')->nullable();
      $table->string('hour')->nullable();
      $table->boolean('is_adjust')->nullable();
      $table->boolean('is_rd')->nullable();
      $table->boolean('is_not_complete')->nullable();
      $table->text('remark')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('xc_task_templates');
  }
}
