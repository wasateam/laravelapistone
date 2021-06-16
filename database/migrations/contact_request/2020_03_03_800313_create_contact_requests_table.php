<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactRequestsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contact_requests', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->string('updated_admin_id')->nullable();
      $table->string('created_admin_id')->nullable();
      $table->string('created_user_id')->nullable();
      $table->integer('user_id')->nullable();
      $table->string('name')->nullable();
      $table->string('email')->nullable();
      $table->string('tel')->nullable();
      $table->text('remark')->nullable();
      $table->string('ip')->nullable();
      $table->string('company_name')->nullable();
      $table->string('budget')->nullable();
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
    Schema::dropIfExists('contact_requests');
  }
}
