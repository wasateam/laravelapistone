<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarkInXcTasksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('xc_tasks', function (Blueprint $table) {
      $table->text('remark')->nullable();
      $table->datetime('time_review_at')->nullable();
      $table->string('tags')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('xc_tasks', function (Blueprint $table) {
      $table->dropColumn('remark');
      $table->dropColumn('time_review_at');
      $table->dropColumn('tags');
    });
  }
}
