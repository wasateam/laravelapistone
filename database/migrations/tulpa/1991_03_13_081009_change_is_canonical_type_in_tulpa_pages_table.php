<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIsCanonicalTypeInTulpaPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tulpa_pages', function (Blueprint $table) {
      $table->text('is_canonical')->nullable()->change();
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
      $table->boolean('is_canonical')->nullable()->change();
    });
  }
}
