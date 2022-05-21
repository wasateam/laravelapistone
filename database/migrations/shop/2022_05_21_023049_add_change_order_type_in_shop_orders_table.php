<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeOrderTypeInShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->string('orderer')->change();
      $table->string('orderer_tel')->change();
      $table->string('orderer_email')->change();
      $table->string('orderer_gender')->change();
      $table->string('receiver')->change();
      $table->string('receiver_tel')->change();
      $table->string('receiver_email')->change();
      $table->string('receiver_gender')->change();
      $table->string('receive_address')->change();
      $table->string('package_way')->change();
      $table->string('receive_way')->change();
      $table->string('ship_way')->change();
      $table->string('pay_type')->change();
      $table->string('pay_status')->change();
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
      $table->text('orderer')->change();
      $table->text('orderer_tel')->change();
      $table->text('orderer_email')->change();
      $table->text('orderer_gender')->change();
      $table->text('receiver')->change();
      $table->text('receiver_tel')->change();
      $table->text('receiver_email')->change();
      $table->text('receiver_gender')->change();
      $table->text('receive_address')->change();
      $table->text('package_way')->change();
      $table->text('receive_way')->change();
      $table->text('ship_way')->change();
      $table->text('pay_type')->change();
      $table->text('pay_status')->change();
    });
  }
}
