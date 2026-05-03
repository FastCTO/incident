<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->foreignId('site_id')
                ->nullable()
                ->after('organization_id')
                ->constrained('sites')
                ->nullOnDelete();

            $table->index('site_id');
        });

        $incidents = DB::table('incidents')->get();

        foreach ($incidents as $incident) {
            if (!$incident->organization_id) {
                continue;
            }

            $siteId = DB::table('sites')
                ->where('organization_id', $incident->organization_id)
                ->orderBy('id')
                ->value('id');

            if ($siteId) {
                DB::table('incidents')
                    ->where('id', $incident->id)
                    ->update([
                        'site_id' => $siteId,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropIndex(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
