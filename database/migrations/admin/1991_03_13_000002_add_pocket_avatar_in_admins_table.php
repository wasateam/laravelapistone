<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPocketAvatarInAdminsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('admins', function (Blueprint $table) {
      $table->integer('pocket_avatar_id')->nullable();
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
      $table->dropColumn('pocket_avatar_id');
    });
  }
}
