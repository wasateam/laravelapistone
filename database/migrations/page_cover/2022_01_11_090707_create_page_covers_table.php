<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageCoversTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('page_covers', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->text('name')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->boolean('is_active')->default(1);
      $table->text('link')->nullable();
      $table->text('image')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('page_covers');
  }
}
