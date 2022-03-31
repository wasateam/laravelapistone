<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifyTodayAppointmentsAtInServiceStoresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->timestamp('today_appointments_notify_at')->nullable();
      $table->timestamp('tomorrow_appointments_notify_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->dropColumn('today_appointments_notify_at');
      $table->dropColumn('tomorrow_appointments_notify_at');
    });
  }
}
