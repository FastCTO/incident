<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incident_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('event_type', 100);
            $table->text('description')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_method', 20)->nullable();
            $table->string('request_path')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('incident_id');
            $table->index('user_id');
            $table->index('event_type');
            $table->index('ip_address');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_events');
    }
};
