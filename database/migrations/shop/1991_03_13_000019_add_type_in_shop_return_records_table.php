<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeInShopReturnRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_return_records', function (Blueprint $table) {
      $table->text('type')->nullable();
      $table->text('status')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_return_records', function (Blueprint $table) {
      $table->dropColumn('type');
      $table->dropColumn('status');
    });
  }
}
