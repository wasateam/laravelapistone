<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrderShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_order_shop_product', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->integer('shop_order_id');
      $table->integer('shop_product_id');
      $table->integer('count')->nullable();
      $table->string('name')->nullable();
      $table->string('subtitle')->nullable();
      $table->string('spec')->nullable();
      $table->status('status')->nullable();
      $table->integer('price')->nullable();
      $table->integer('discount_price')->nullable();
      $table->string('weight_capacity')->nullable();
      $table->text('cover_image')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_order_shop_product');
  }
}
