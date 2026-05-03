<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incident_events', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('description');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('request_method', 20)->nullable()->after('user_agent');
            $table->string('request_path')->nullable()->after('request_method');

            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('incident_events', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);

            $table->dropColumn([
                'ip_address',
                'user_agent',
                'request_method',
                'request_path',
            ]);
        });
    }
};
