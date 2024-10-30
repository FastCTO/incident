<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->boolean('substitute')->default(false);
            $table->boolean('vendor')->default(false);
            $table->boolean('freq_visitor')->default(false);
            $table->string('mobile')->nullable();
            $table->string('working_room')->nullable();
            $table->string('guest_of')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guests');
    }
}

