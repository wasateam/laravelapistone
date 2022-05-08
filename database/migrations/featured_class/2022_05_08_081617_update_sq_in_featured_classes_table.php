<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSqInFeaturedClassesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('featured_classes', function (Blueprint $table) {
      $table->renameColumn('sequence', 'sq');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('featured_classes', function (Blueprint $table) {
      $table->renameColumn('sq', 'sequence');
    });
  }
}
