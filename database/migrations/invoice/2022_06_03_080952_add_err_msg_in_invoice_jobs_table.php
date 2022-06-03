<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddErrMsgInInvoiceJobsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('invoice_jobs', function (Blueprint $table) {
      $table->text('err_msg')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('invoice_jobs', function (Blueprint $table) {
      $table->dropColumn('err_msg');
    });
  }
}
