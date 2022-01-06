<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeInShopCampaignShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_campaign_shop_orders', function (Blueprint $table) {
      $table->text('type')->nullable();
      $table->text('name')->nullable();
      $table->string('condition')->nullable();
      $table->integer('full_amount')->nullable();
      $table->integer('discount_percent')->nullable();
      $table->integer('discount_amount')->nullable();
      $table->float('feedback_rate', 8, 3)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_campaign_shop_orders', function (Blueprint $table) {
      $table->dropColumn('type');
      $table->dropColumn('name');
      $table->dropColumn('condition');
      $table->dropColumn('full_amount');
      $table->dropColumn('discount_percent');
      $table->dropColumn('discount_amount');
      $table->dropColumn('feedback_rate');
    });
  }
}
