<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->string('name');
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->integer('status')->default(0);
      $table->text('avatar')->nullable();
      $table->text('settings')->nullable();
      $table->text('scopes')->nullable(['wasa']);
      $table->string('firestore_id')->nullable();
      $table->string('tel')->nullable();
      $table->text('payload')->nullable();
      $table->rememberToken();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('admins');
  }
}
