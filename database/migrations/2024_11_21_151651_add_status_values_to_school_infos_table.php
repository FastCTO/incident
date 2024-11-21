<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusValuesToSchoolInfosTable extends Migration
{
    public function up()
    {
        Schema::table('school_infos', function (Blueprint $table) {
            $table->string('status')->default('Normal')->change();
        });
    }

    public function down()
    {
        Schema::table('school_infos', function (Blueprint $table) {
            $table->string('status')->default(null)->change();
        });
    }
}

