<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('incident_type')->nullable();
            $table->string('status')->default('open');

            $table->string('location_name')->nullable();
            $table->string('address')->nullable();

            $table->dateTime('incident_datetime')->nullable();

            $table->text('summary')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('incident_type');
            $table->index('incident_datetime');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
