<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_organization_id')->nullable();

            $table->string('name');
            $table->string('organization_type')->default('customer');
            $table->string('status')->default('active');

            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            $table->string('website')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable()->default('US');

            $table->string('logo_path')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('parent_organization_id')
                ->references('id')
                ->on('organizations')
                ->nullOnDelete();

            $table->index('parent_organization_id');
            $table->index('organization_type');
            $table->index('status');
            $table->index('name');
        });

        DB::table('organizations')->insert([
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

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
