<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPersonalInXcMilestonesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('xc_milestones', function (Blueprint $table) {
      $table->boolean('is_personal')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('xc_milestones', function (Blueprint $table) {
      $table->dropColumn('is_personal');
    });
  }
}
