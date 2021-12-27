<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifyMailsInServiceStoresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->text('notify_emails')->nullable();
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
      $table->dropColumn('notify_emails');
    });
  }
}
