<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowcasesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('showcases', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('sq')->nullable();
      $table->string('name')->nullable();
      $table->text('description')->nullable();
      $table->string('color')->nullable();
      $table->text('cover_image')->nullable();
      $table->text('main_image')->nullable();
      $table->string('route_name')->unique();
      $table->text('tags')->nullable();
      $table->boolean('is_active')->default(0);
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
    Schema::dropIfExists('showcases');
  }
}
