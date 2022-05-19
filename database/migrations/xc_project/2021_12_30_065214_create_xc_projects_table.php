<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXcProjectsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('xc_projects', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('url')->nullable()->unique();
      $table->string('name')->nullable();
      $table->text('slack_webhook_url')->nullable();
      $table->string('status')->nullable();
      $table->text('slack_team_id')->nullable();
      $table->text('slack_channel_id')->nullable();
      $table->text('invision')->nullable();
      $table->text('zeplin')->nullable();
      $table->text('gitlab')->nullable();
      $table->text('github')->nullable();
      $table->text('google_drive')->nullable();
      $table->text('remark')->nullable();
      $table->longText('links')->nullable();
      $table->longText('infos')->nullable();
      $table->text('payload')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('xc_projects');
  }
}
