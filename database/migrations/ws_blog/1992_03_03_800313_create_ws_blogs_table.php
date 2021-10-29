<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsBlogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ws_blogs', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->string('title');
      $table->text('description')->nullable();
      $table->datetime('publish_at')->nullable();
      $table->integer('read_count')->default(0);
      $table->integer('cover_image_id')->nullable();
      $table->longText('content')->nullable();
      $table->text('rough_content')->nullable();
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
    Schema::dropIfExists('ws_blogs');
  }
}
