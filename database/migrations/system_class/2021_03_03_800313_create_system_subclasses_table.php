<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSubclassesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('system_subclasses', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->integer('created_admin_id')->nullable();
      $table->string('sequence')->nullable();
      $table->integer('area_id')->nullable();
      $table->integer('system_class_id')->nullable();
      $table->string('name')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('system_subclasses');
  }
}
