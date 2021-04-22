<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePocketImageVersionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pocket_image_versions', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->integer('pocket_image_id');
      $table->string('updated_admin_id')->nullable();
      $table->string('created_admin_id')->nullable();
      $table->string('created_user_id')->nullable();
      $table->boolean('signed')->default(0);
      $table->text('signed_url')->nullable();
      $table->text('url')->nullable();
      $table->string('name');
      $table->string('size')->nullable();
      $table->text('tags')->nullable();
      $table->boolean('is_eternal')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pocket_image_versions');
  }
}
