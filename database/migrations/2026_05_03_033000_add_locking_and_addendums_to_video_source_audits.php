<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_source_audits', function (Blueprint $table) {
            if (!Schema::hasColumn('video_source_audits', 'locked_at')) {
                $table->timestamp('locked_at')->nullable()->after('next_audit_due_at');
            }

            if (!Schema::hasColumn('video_source_audits', 'locked_by')) {
                $table->unsignedBigInteger('locked_by')->nullable()->after('locked_at');
            }
        });

        if (!$this->tableExists('video_source_audit_addendums')) {
            Schema::create('video_source_audit_addendums', function (Blueprint $table) {
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

                $table->unsignedBigInteger('added_by')->nullable();

                $table->string('addendum_type')->default('note');
                $table->text('body');

                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('request_method', 20)->nullable();
                $table->string('request_path')->nullable();

                $table->timestamps();

                $table->index('video_source_audit_id', 'vsa_addendum_audit_id_index');
                $table->index('nvr_system_id', 'vsa_addendum_nvr_system_id_index');
                $table->index('organization_id', 'vsa_addendum_organization_id_index');
                $table->index('site_id', 'vsa_addendum_site_id_index');
                $table->index('added_by', 'vsa_addendum_added_by_index');
                $table->index('addendum_type', 'vsa_addendum_type_index');
                $table->index('ip_address', 'vsa_addendum_ip_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('video_source_audit_addendums');

        Schema::table('video_source_audits', function (Blueprint $table) {
            if (Schema::hasColumn('video_source_audits', 'locked_at')) {
                $table->dropColumn('locked_at');
            }

            if (Schema::hasColumn('video_source_audits', 'locked_by')) {
                $table->dropColumn('locked_by');
            }
        });
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }
};
