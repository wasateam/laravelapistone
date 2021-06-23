<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanonicalUrlInTulpaPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('tulpa_pages', function (Blueprint $table) {
      $table->text('canonical_url')->nullable();
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
      $table->dropColumn('canonical_url');
    });
  }
}
