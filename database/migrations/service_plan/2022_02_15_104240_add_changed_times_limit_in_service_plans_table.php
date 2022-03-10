<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangedTimesLimitInServicePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_plans', function (Blueprint $table) {
            $table->integer('user_device_bind_count_limit')->nullable();
            $table->integer('user_device_update_count_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_plans', function (Blueprint $table) {
            $table->dropColumn('user_device_bind_count_limit');
            $table->dropColumn('user_device_update_count_limit');
        });
    }
}
