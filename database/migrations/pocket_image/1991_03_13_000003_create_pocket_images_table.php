<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePocketImagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pocket_images', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->string('created_admin_id')->nullable();
      $table->string('created_user_id')->nullable();
      $table->boolean('signed')->default(0);
      $table->text('signed_url')->nullable();
      $table->text('url')->nullable();
      $table->string('name');
      $table->text('tags')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pocket_images');
  }
}
