<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueInShopProductImportRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_product_import_records', function (Blueprint $table) {
      $table->dropColumn('uuid');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_product_import_records', function (Blueprint $table) {
      $table->uuid('uuid')->nullable()->unique();
    });
  }
}
