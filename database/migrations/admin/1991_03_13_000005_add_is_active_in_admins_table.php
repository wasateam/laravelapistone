<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveInAdminsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('admins', function (Blueprint $table) {
      $table->integer('is_active')->default(0);
      $table->string('sequence')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('admins', function (Blueprint $table) {
      $table->dropColumn('is_active');
      $table->dropColumn('sequence');
    });
  }
}
