<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePocketImageAdminFinancePaymentRequestTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('pocket_image_admin_finance_payment_request', function (Blueprint $table) {
      $table->string('pocket_image_id');
      $table->string('admin_finance_payment_request_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pocket_image_admin_finance_payment_request');
  }
}
