<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryParticipantsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lottery_participants', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('id_number')->nullable();
      $table->string('name')->nullable();
      $table->string('genger')->nullable();
      $table->date('birthday')->nullable();
      $table->string('email')->nullable();
      $table->uuid('uuid')->nullable();
      $table->string('qualifications')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('lottery_participants');
  }
}
