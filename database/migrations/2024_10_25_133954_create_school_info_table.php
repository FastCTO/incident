<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

	public function up()
{
    Schema::create('school_info', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('address');
        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);
        $table->integer('total_floors');
        $table->integer('max_capacity');
        $table->time('student_hours_start');
        $table->time('student_hours_end');
        $table->time('activity_hours_start')->nullable();
        $table->time('activity_hours_end')->nullable();
        $table->time('cleaning_hours_start')->nullable();
        $table->time('cleaning_hours_end')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('school_info');
}

};
