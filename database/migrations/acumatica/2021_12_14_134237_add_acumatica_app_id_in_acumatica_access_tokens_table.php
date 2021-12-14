<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcumaticaAppIdInAcumaticaAccessTokensTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('acumatica_access_tokens', function (Blueprint $table) {
      $table->string('acumatica_app_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('acumatica_access_tokens', function (Blueprint $table) {
      $table->dropColumn('acumatica_app_id');
    });
  }
}
