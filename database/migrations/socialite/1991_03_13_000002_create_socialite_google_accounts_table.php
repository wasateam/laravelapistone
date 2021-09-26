<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialiteGoogleAccountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('socialite_google_accounts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->integer('user_id');
      $table->string('provider_user_id');
      $table->string('provider');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('socialite_google_accounts');
  }
}
