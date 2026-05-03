<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_source_audits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nvr_system_id')
                ->constrained('nvr_systems')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->foreignId('site_id')
                ->constrained('sites')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('performed_by')->nullable();

            $table->string('audit_type')->default('initial_baseline');
            $table->string('audit_status')->default('pass');

            $table->timestamp('performed_at')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_method', 20)->nullable();
            $table->string('request_path')->nullable();

            $table->string('serial_number_observed')->nullable();
            $table->string('manufacturer_observed')->nullable();
            $table->string('model_observed')->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('software_version')->nullable();
            $table->string('os_version')->nullable();

            $table->string('hostname_observed')->nullable();
            $table->string('source_ip_observed')->nullable();
            $table->string('mac_address_observed')->nullable();

            $table->timestamp('system_datetime')->nullable();
            $table->string('system_time_zone')->nullable();
            $table->text('time_drift_notes')->nullable();

            $table->string('total_storage')->nullable();
            $table->string('used_storage')->nullable();
            $table->string('available_storage')->nullable();
            $table->string('storage_health')->nullable();

            $table->timestamp('oldest_recording_at')->nullable();
            $table->unsignedInteger('estimated_retention_days')->nullable();
            $table->string('recording_mode')->nullable();

            $table->unsignedInteger('total_camera_count')->nullable();
            $table->unsignedInteger('active_camera_count')->nullable();
            $table->unsignedInteger('offline_camera_count')->nullable();
            $table->unsignedInteger('disabled_camera_count')->nullable();

            $table->text('camera_view_notes')->nullable();

            $table->unsignedInteger('admin_user_count')->nullable();
            $table->unsignedInteger('standard_user_count')->nullable();
            $table->unsignedInteger('unknown_user_count')->nullable();

            $table->text('last_login_notes')->nullable();
            $table->text('failed_login_notes')->nullable();
            $table->text('unusual_activity_notes')->nullable();
            $table->text('security_notes')->nullable();
            $table->text('retention_notes')->nullable();
            $table->text('overall_notes')->nullable();
            $table->text('recommended_actions')->nullable();

            $table->timestamp('next_audit_due_at')->nullable();

            $table->timestamps();

            $table->index('nvr_system_id');
            $table->index('organization_id');
            $table->index('site_id');
            $table->index('performed_by');
            $table->index('audit_type');
            $table->index('audit_status');
            $table->index('performed_at');
            $table->index('next_audit_due_at');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_source_audits');
    }
};
