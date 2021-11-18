<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeInvoiceInfosInShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->dropColumn('receipt_number');
      $table->dropColumn('receipt_status');
      $table->dropColumn('receipt_type');
      $table->dropColumn('receipt_carrier_number');
      $table->dropColumn('receipt_tax');
      $table->dropColumn('receipt_title');
      $table->string('invoice_number')->nullable();
      $table->string('invoice_status')->nullable();
      $table->string('invoice_type')->nullable();
      $table->string('invoice_carrier_number')->nullable();
      $table->string('invoice_tax_type')->nullable();
      $table->string('invoice_title')->nullable();
      $table->string('invoice_company_name')->nullable();
      $table->text('invoice_address')->nullable();
      $table->string('invoice_uniform_number')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->text('receipt_number')->nullable();
      $table->text('receipt_status')->nullable();
      $table->text('receipt_type')->nullable();
      $table->text('receipt_carrier_number')->nullable();
      $table->text('receipt_tax')->nullable();
      $table->text('receipt_title')->nullable();
      $table->dropColumn('invoice_number');
      $table->dropColumn('invoice_status');
      $table->dropColumn('invoice_type');
      $table->dropColumn('invoice_carrier_number');
      $table->dropColumn('invoice_tax_type');
      $table->dropColumn('invoice_title');
      $table->dropColumn('invoice_company_name');
      $table->dropColumn('invoice_address');
      $table->dropColumn('invoice_uniform_number');
    });
  }
}
