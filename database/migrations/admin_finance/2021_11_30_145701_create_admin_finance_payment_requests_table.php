<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminFinancePaymentRequestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('admin_finance_payment_requests', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('admin_id')->nullable();
      $table->string('status')->nullable();
      $table->string('invoice_type')->nullable();
      $table->string('paying_type')->nullable();
      $table->string('amount')->nullable();
      $table->date('paying_date')->nullable();
      $table->date('verify_date')->nullable();
      $table->date('complete_date')->nullable();
      $table->text('request_remark')->nullable();
      $table->text('reviewer_id')->nullable();
      $table->text('review_remark')->nullable();
      $table->text('payload')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('admin_finance_payment_requests');
  }
}
