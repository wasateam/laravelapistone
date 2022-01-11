<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcMilestonesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('xc_milestones', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('name')->nullable();
      $table->date('date')->nullable();
      $table->timestamp('reviewed_at')->nullable();
      $table->string('status')->nullable();
      $table->string('creator_id')->nullable();
      $table->string('taker_id')->nullable();
      $table->string('xc_project_id')->nullable();
      $table->text('creator_remark')->nullable();
      $table->text('taker_remark')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('xc_milestones');
  }
}
