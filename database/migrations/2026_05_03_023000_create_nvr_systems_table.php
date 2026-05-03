<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nvr_systems', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->foreignId('site_id')
                ->constrained('sites')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('system_type')->nullable();
            $table->string('status')->default('active');

            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();

            $table->string('hostname')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('local_url')->nullable();
            $table->string('remote_url')->nullable();

            $table->unsignedInteger('camera_count')->nullable();
            $table->unsignedInteger('estimated_retention_days')->nullable();

            $table->text('storage_notes')->nullable();
            $table->text('access_notes')->nullable();

            $table->timestamp('last_checked_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('organization_id');
            $table->index('site_id');
            $table->index('status');
            $table->index('system_type');
            $table->index('manufacturer');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nvr_systems');
    }
};
