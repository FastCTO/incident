<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('site_type')->nullable();
            $table->string('status')->default('active');

            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable()->default('US');
            $table->string('time_zone')->nullable()->default('America/Chicago');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('organization_id');
            $table->index('status');
            $table->index('name');
        });

        $organizations = DB::table('organizations')->get();

        foreach ($organizations as $organization) {
            DB::table('sites')->insert([
                'organization_id' => $organization->id,
                'name' => $organization->name . ' - Main Site',
                'site_type' => 'main',
                'status' => 'active',
                'contact_name' => $organization->contact_name ?? null,
                'contact_email' => $organization->contact_email ?? null,
                'contact_phone' => $organization->contact_phone ?? null,
                'address' => $organization->address ?? null,
                'city' => $organization->city ?? null,
                'state' => $organization->state ?? null,
                'postal_code' => $organization->postal_code ?? null,
                'country' => $organization->country ?? 'US',
                'time_zone' => 'America/Chicago',
                'notes' => 'Default site created during FSV Incident site setup.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
