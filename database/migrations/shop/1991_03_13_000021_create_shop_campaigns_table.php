<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCampaignsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_campaigns', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->text('type')->nullable();
      $table->text('name')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->string('discount_code')->nullable()->unique();
      $table->string('condition')->nullable();
      $table->integer('full_amount')->nullable();
      $table->integer('discount_percent')->nullable();
      $table->integer('discount_amount')->nullable();
      $table->boolean('is_active')->default(0);
      $table->integer('limit')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_campaigns');
  }
}
