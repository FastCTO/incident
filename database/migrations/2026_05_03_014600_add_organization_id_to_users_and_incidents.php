<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $organizationId = DB::table('organizations')
            ->where('name', 'Focus Secure Video')
            ->value('id');

        if (!$organizationId) {
            $organizationId = DB::table('organizations')->insertGetId([
                'name' => 'Focus Secure Video',
                'organization_type' => 'platform_owner',
                'status' => 'active',
                'contact_name' => 'Vic Herrera',
                'contact_email' => 'vic@fsv.io',
                'country' => 'US',
                'notes' => 'Default organization created during FSV Incident V1 organization setup.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')
                ->nullable()
                ->after('id')
                ->constrained('organizations')
                ->nullOnDelete();

            $table->index('organization_id');
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->foreignId('organization_id')
                ->nullable()
                ->after('id')
                ->constrained('organizations')
                ->nullOnDelete();

            $table->index('organization_id');
        });

        DB::table('users')->whereNull('organization_id')->update([
            'organization_id' => $organizationId,
        ]);

        DB::table('incidents')->whereNull('organization_id')->update([
            'organization_id' => $organizationId,
        ]);
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
