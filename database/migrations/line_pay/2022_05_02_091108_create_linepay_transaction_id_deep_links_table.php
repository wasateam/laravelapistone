<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinepayTransactionIdDeepLinksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('linepay_transaction_id_deep_links', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->string('transaction_id');
      $table->string('deep_link');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('linepay_transaction_id_deep_links');
  }
}
