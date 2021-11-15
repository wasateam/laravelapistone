<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCartProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_cart_products', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->integer('created_user_id')->nullable();
      $table->integer('updated_user_id')->nullable();
      $table->integer('shop_cart_id')->nullable();
      $table->integer('shop_product_id')->nullable();
      $table->string('name')->nullable();
      $table->string('subtitle')->nullable();
      $table->integer('count')->nullable();
      $table->integer('price')->nullable();
      $table->integer('discount_price')->nullable();
      $table->integer('status')->default(1);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_cart_products');
  }
}
