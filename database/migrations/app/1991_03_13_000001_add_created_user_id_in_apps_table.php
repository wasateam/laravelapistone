<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedUserIdInAppsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('apps', function (Blueprint $table) {
      $table->string('created_user_id')->nullable();
      $table->longText('setting')->nullable();
      $table->longText('payload')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('apps', function (Blueprint $table) {
      $table->dropColumn('created_user_id');
      $table->dropColumn('setting');
      $table->dropColumn('payload');
    });
  }
}
