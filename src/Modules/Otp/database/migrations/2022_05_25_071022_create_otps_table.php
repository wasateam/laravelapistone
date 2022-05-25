<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('otps', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->boolean('is_active')->nullable();
      $table->string('content')->nullable();
      $table->string('usage')->nullable();
      $table->integer('user_id')->nullable();
      $table->datetime('expired_at')->nullable();
      $table->index('expired_at', 'idx_expired_at');
      $table->index('usage', 'idx_usage');
      $table->index('user_id', 'idx_user_id');
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
    Schema::dropIfExists('otps');
  }
}
