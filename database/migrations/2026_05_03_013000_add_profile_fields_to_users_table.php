<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('cell_phone')->nullable()->after('email');
            $table->string('organization_name')->nullable()->after('cell_phone');
            $table->string('role_title')->nullable()->after('organization_name');

            $table->index('organization_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['organization_name']);

            $table->dropColumn([
                'first_name',
                'last_name',
                'cell_phone',
                'organization_name',
                'role_title',
            ]);
        });
    }
};
