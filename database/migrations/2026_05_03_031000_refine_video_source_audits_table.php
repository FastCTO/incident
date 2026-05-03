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
            if (!Schema::hasColumn('video_source_audits', 'installation_date')) {
                $table->date('installation_date')->nullable()->after('mac_address_observed');
            }

            if (!Schema::hasColumn('video_source_audits', 'ntp_enabled')) {
                $table->boolean('ntp_enabled')->nullable()->after('system_time_zone');
            }

            if (!Schema::hasColumn('video_source_audits', 'total_storage_amount')) {
                $table->string('total_storage_amount')->nullable()->after('time_drift_notes');
            }

            if (!Schema::hasColumn('video_source_audits', 'total_storage_unit')) {
                $table->string('total_storage_unit')->nullable()->after('total_storage_amount');
            }

            if (!Schema::hasColumn('video_source_audits', 'storage_status')) {
                $table->string('storage_status')->nullable()->after('total_storage_unit');
            }

            if (!Schema::hasColumn('video_source_audits', 'oldest_recording_verified')) {
                $table->boolean('oldest_recording_verified')->nullable()->after('oldest_recording_at');
            }

            if (!Schema::hasColumn('video_source_audits', 'export_test_performed')) {
                $table->boolean('export_test_performed')->nullable()->after('recording_mode');
            }

            if (!Schema::hasColumn('video_source_audits', 'export_test_status')) {
                $table->string('export_test_status')->nullable()->after('export_test_performed');
            }

            if (!Schema::hasColumn('video_source_audits', 'export_test_notes')) {
                $table->text('export_test_notes')->nullable()->after('export_test_status');
            }

            if (!Schema::hasColumn('video_source_audits', 'logs_reviewed')) {
                $table->boolean('logs_reviewed')->nullable()->after('security_notes');
            }

            if (!Schema::hasColumn('video_source_audits', 'log_review_window')) {
                $table->string('log_review_window')->nullable()->after('logs_reviewed');
            }

            if (!Schema::hasColumn('video_source_audits', 'log_notes')) {
                $table->text('log_notes')->nullable()->after('log_review_window');
            }
        });

        if (!$this->indexExists('video_source_audits', 'video_source_audits_installation_date_index')) {
            Schema::table('video_source_audits', function (Blueprint $table) {
                $table->index('installation_date');
            });
        }

        /*
         * Do not add next_audit_due_at index here.
         * It already exists from the original video_source_audits migration.
         */
    }

    public function down(): void
    {
        Schema::table('video_source_audits', function (Blueprint $table) {
            if ($this->indexExists('video_source_audits', 'video_source_audits_installation_date_index')) {
                $table->dropIndex('video_source_audits_installation_date_index');
            }
        });

        Schema::table('video_source_audits', function (Blueprint $table) {
            $columns = [
                'installation_date',
                'ntp_enabled',
                'total_storage_amount',
                'total_storage_unit',
                'storage_status',
                'oldest_recording_verified',
                'export_test_performed',
                'export_test_status',
                'export_test_notes',
                'logs_reviewed',
                'log_review_window',
                'log_notes',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('video_source_audits', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        $results = DB::select(
            'SELECT INDEX_NAME FROM information_schema.statistics WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$database, $table, $indexName]
        );

        return count($results) > 0;
    }
};
