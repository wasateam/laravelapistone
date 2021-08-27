<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStoresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('service_stores', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid('uuid')->nullable();
      $table->string('updated_admin_id')->nullable();
      $table->string('created_admin_id')->nullable();
      $table->string('name')->nullable();
      $table->string('type')->nullable();
      $table->text('cover_image')->nullable();
      $table->text('tel')->nullable();
      $table->text('address')->nullable();
      $table->text('des')->nullable();
      $table->text('business_hours')->nullable();
      $table->string('lat')->nullable();
      $table->string('lng')->nullable();
      $table->boolean('is_active')->nullable()->default(1);
      $table->longText('payload')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('service_stores');
  }
}
