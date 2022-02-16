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
            $table->integer('changed_times_limit')->nullable();
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
        Schema::table('service_plans', function (Blueprint $table) {
            $table->dropColumn('changed_times_limit');
            $table->dropColumn('limit');
        });
    }
}
