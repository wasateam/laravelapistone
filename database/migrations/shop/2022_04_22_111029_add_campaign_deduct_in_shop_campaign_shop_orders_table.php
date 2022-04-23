<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampaignDeductInShopCampaignShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_campaign_shop_orders', function (Blueprint $table) {
      $table->string('campaign_deduct')->nullable();
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
      $table->dropColumn('campaign_deduct');
    });
  }
}
