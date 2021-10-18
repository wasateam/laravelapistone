<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBadInUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $talbe->boolean('is_bad')->nullable()->default(0);
      $table->integer('bonus_points')->nullable()->default(0);
      $table->string('birthday')->nullable();
      $table->uuid('uuid')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('is_bad');
      $table->dropColumn('bonus_points');
      $table->dropColumn('birthday');
      $table->dropColumn('uuid');
    });
  }
}
