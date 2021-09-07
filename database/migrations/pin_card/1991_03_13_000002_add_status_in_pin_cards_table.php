<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusInPinCardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('pin_cards', function (Blueprint $table) {
      $table->string('status')->nullable()->default('0');
      $table->string('user_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('pin_cards', function (Blueprint $table) {
      $table->dropColumn('status');
      $table->dropColumn('user_id');
    });
  }
}
