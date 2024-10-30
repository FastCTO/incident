<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchoolOccupancyDefaultInEmergencies extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->integer('school_occupancy')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->integer('school_occupancy')->change();
        });
    }
}

