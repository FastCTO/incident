<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('role')->default('user');
            $table->tinyInteger('emerg_notify')->default(0);
            $table->string('current_room', 50)->nullable();
            $table->string('home_room', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title', 
                'role', 
                'emerg_notify', 
                'current_room', 
                'home_room'
            ]);
        });
    }
}

