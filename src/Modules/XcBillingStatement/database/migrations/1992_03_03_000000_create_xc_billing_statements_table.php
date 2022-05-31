<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcBillingStatementsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('xc_billing_statements', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->integer('invoice_type')->nullable()->default(1);
      $table->text('images')->nullable();
      $table->integer('pay_type')->nullable();
      $table->integer('amount')->nullable();
      $table->datetime('pay_at')->nullable();
      $table->datetime('review_at')->nullable();
      $table->text('remark')->nullable();
      $table->integer('review_status')->nullable();
      $table->integer('admin_id')->nullable();
      $table->integer('reviewer_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('xc_billing_statements');
  }
}
