<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppDevelopersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_developers', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->boolean('is_active')->nullable()->default(1);
      $table->string('mobile')->nullable();
      $table->string('mobile_country_code')->nullable();
      $table->string('otp')->nullable();
      $table->index('mobile_country_code', 'idx_mobile_country_code');
      $table->index('is_active', 'idx_is_active');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('app_developers');
  }
}
