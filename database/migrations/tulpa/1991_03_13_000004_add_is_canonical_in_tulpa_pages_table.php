<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCanonicalInTulpaPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tulpa_pages', function (Blueprint $table) {
      $table->boolean('is_canonical')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('tulpa_pages', function (Blueprint $table) {
      $table->dropColumn('is_canonical');
    });
  }
}
