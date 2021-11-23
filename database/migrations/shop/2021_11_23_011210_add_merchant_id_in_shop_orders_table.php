<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMerchantIdInShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->string('ecpay_merchant_id')->nullable();
      $table->string('ecpay_trade_no')->nullable();
      $table->string('ecpay_charge_fee')->nullable();
      $table->datetime('pay_at')->nullable();
      $table->string('csv_pay_from')->nullable();
      $table->string('csv_payment_no')->nullable();
      $table->text('csv_payment_url')->nullable();
      $table->string('barcode_pay_from')->nullable();
      $table->string('atm_acc_bank')->nullable();
      $table->string('atm_acc_no')->nullable();
      $table->string('card_auth_code')->nullable();
      $table->string('card_gwsr')->nullable();
      $table->datetime('card_process_at')->nullable();
      $table->string('card_amount')->nullable();
      $table->string('card_pre_six_no')->nullable();
      $table->string('card_last_four_no')->nullable();
      $table->string('card_stage')->nullable();
      $table->string('card_stast')->nullable();
      $table->string('card_staed')->nullable();
      $table->string('card_red_dan')->nullable();
      $table->string('card_red_de_amt')->nullable();
      $table->string('card_red_ok_amt')->nullable();
      $table->string('card_red_yet')->nullable();
      $table->string('card_period_type')->nullable();
      $table->string('card_frequency')->nullable();
      $table->string('card_exec_times')->nullable();
      $table->string('card_period_amount')->nullable();
      $table->string('card_total_success_times')->nullable();
      $table->string('card_total_success_amount')->nullable();
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
      $table->dropColumn('ecpay_merchant_id');
      $table->dropColumn('ecpay_trade_no');
      $table->dropColumn('ecpay_charge_fee');
      $table->dropColumn('pay_at');
      $table->dropColumn('csv_pay_from');
      $table->dropColumn('csv_payment_no');
      $table->dropColumn('csv_payment_url');
      $table->dropColumn('barcode_pay_from');
      $table->dropColumn('atm_acc_bank');
      $table->dropColumn('atm_acc_no');
      $table->dropColumn('card_auth_code');
      $table->dropColumn('card_gwsr');
      $table->dropColumn('card_process_at');
      $table->dropColumn('card_amount');
      $table->dropColumn('card_pre_six_no');
      $table->dropColumn('card_last_four_no');
      $table->dropColumn('card_stage');
      $table->dropColumn('card_stast');
      $table->dropColumn('card_staed');
      $table->dropColumn('card_red_dan');
      $table->dropColumn('card_red_de_amt');
      $table->dropColumn('card_red_ok_amt');
      $table->dropColumn('card_red_yet');
      $table->dropColumn('card_period_type');
      $table->dropColumn('card_frequency');
      $table->dropColumn('card_exec_times');
      $table->dropColumn('card_period_amount');
      $table->dropColumn('card_total_success_times');
      $table->dropColumn('card_total_success_amount');
    });
  }
}
