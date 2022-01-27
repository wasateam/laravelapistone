<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThePointRecordsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('the_point_records', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('type')->nullable();
      $table->string('source')->nullable();
      $table->integer('user_id')->nullable();
      $table->integer('shop_order_id')->nullable();
      $table->integer('shop_campaign_id')->nullable();
      $table->integer('count')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('the_point_records');
  }
}
