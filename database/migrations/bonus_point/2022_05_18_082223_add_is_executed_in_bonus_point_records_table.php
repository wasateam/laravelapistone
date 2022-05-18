<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsExecutedInBonusPointRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('bonus_point_records', function (Blueprint $table) {
      $table->boolean('is_executed')->nullable()->default(1);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('bonus_point_records', function (Blueprint $table) {
      $table->dropColumn('is_executed');
    });
  }
}
