<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarrierInUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->text('carrier_email')->nullable();
      $table->text('carrier_phone')->nullable();
      $table->text('carrier_certificate')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('carrier_email');
      $table->dropColumn('carrier_phone');
      $table->dropColumn('carrier_certificate');
    });
  }
}
