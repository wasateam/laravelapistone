<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedbackRateInShopCampaignsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_campaigns', function (Blueprint $table) {
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
    Schema::table('shop_campaigns', function (Blueprint $table) {
      $table->dropColumn('feedback_rate');
    });
  }
}
