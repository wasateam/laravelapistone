<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTulpaSectionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tulpa_sections', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->integer('created_admin_id')->nullable();
      $table->string('name')->nullable();
      $table->string('component_name')->nullable();
      $table->text('remark')->nullable();
      $table->text('fields')->nullable();
      $table->text('content')->nullable();
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
    Schema::dropIfExists('tulpa_sections');
  }
}
