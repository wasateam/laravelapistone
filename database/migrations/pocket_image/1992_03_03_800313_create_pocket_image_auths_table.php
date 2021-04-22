<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePocketImageAuthsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pocket_image_auths', function (Blueprint $table) {
      $table->integer('poc_img_id');
      $table->integer('tr_typ');
      $table->integer('tr_id');
      $table->integer('scopes');
      $table->unique(['poc_img_id', 'tr_typ', 'tr_id', 'scopes']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pocket_image_auths');
  }
}
