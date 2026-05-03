<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_source_audit_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('video_source_audit_id')
                ->constrained('video_source_audits')
                ->cascadeOnDelete();

            $table->foreignId('nvr_system_id')
                ->constrained('nvr_systems')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->foreignId('site_id')
                ->constrained('sites')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('uploaded_by')->nullable();

            $table->string('attachment_type')->default('other');

            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');

            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('sha256_hash', 64)->nullable();

            $table->text('notes')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_method', 20)->nullable();
            $table->string('request_path')->nullable();

            $table->timestamps();

            $table->index('video_source_audit_id', 'vsa_attach_audit_id_index');
            $table->index('nvr_system_id', 'vsa_attach_nvr_system_id_index');
            $table->index('organization_id', 'vsa_attach_organization_id_index');
            $table->index('site_id', 'vsa_attach_site_id_index');
            $table->index('uploaded_by', 'vsa_attach_uploaded_by_index');
            $table->index('attachment_type', 'vsa_attach_type_index');
            $table->index('sha256_hash', 'vsa_attach_hash_index');
            $table->index('ip_address', 'vsa_attach_ip_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_source_audit_attachments');
    }
};
