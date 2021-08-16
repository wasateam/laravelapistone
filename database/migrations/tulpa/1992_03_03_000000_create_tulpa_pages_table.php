<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTulpaPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tulpa_pages', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->integer('created_admin_id')->nullable();
      $table->integer('tulpa_page_template_id')->nullable();
      $table->string('name')->nullable();
      $table->string('route')->nullable()->unique();
      $table->text('canonical_url')->nullable();
      $table->string('title')->nullable();
      $table->text('description')->nullable();
      $table->text('og_image')->nullable();
      $table->boolean('is_active')->default(0);
      $table->text('tags')->nullable();
      $table->text('remark')->nullable();
      $table->text('content')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('tulpa_pages');
  }
}
