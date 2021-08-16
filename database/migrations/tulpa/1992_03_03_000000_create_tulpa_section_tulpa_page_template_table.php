<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTulpaSectionTulpaPageTemplateTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tulpa_section_tulpa_page_template', function (Blueprint $table) {
      $table->integer('tulpa_section_id');
      $table->integer('tulpa_page_template_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('tulpa_section_tulpa_page_template');
  }
}
