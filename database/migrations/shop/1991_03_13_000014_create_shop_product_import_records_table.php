<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductImportRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_product_import_records', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid('uuid')->nullable()->unique();
      $table->string('no')->nullable();
      $table->integer('shop_product_id')->nullable();
      $table->integer('stock_count')->nullable();
      $table->string('storage_space')->nullable();
      $table->integer('created_admin_id')->nullable();
      $table->integer('updated_admin_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_product_import_records');
  }
}
