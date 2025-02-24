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
        Schema::table('school_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('school_infos', 'status')) {
                $table->enum('status', ['Emergency', 'Normal'])->default('Normal');  // New column for emergency status
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'floor_number')) {
                $table->integer('floor_number')->nullable();  // Add floor number to rooms table
            }
        });

        if (!Schema::hasTable('emergencies')) {
            Schema::create('emergencies', function (Blueprint $table) {
                $table->id();
                $table->enum('emergency_type', ['Active Shooter', 'Tornado', 'Medical']);
                $table->text('description');
                $table->string('reporting_phone')->nullable();
                $table->foreignId('reporting_user_id')->constrained('users')->onDelete('cascade');
                $table->integer('room_occupancy');
                $table->integer('school_occupancy');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('school_infos', function (Blueprint $table) {
            if (Schema::hasColumn('school_infos', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'floor_number')) {
                $table->dropColumn('floor_number');
            }
        });

        if (Schema::hasTable('emergencies')) {
            Schema::dropIfExists('emergencies');
        }
    }
};

