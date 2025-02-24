<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('invites')) {
            // ✅ Only create if the table doesn't exist
            Schema::create('invites', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->nullable();  // Email is optional
                $table->string('cell_number');  // For SMS invites
                $table->unsignedBigInteger('invited_by');  // References the user sending the invite
                $table->string('token')->unique();
                $table->boolean('accepted')->default(false);
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                // Add foreign key to the 'users' table
                $table->foreign('invited_by')->references('id')->on('users')->onDelete('cascade');
            });
        } else {
            // ✅ If table exists, check and add missing columns
            Schema::table('invites', function (Blueprint $table) {
                if (!Schema::hasColumn('invites', 'first_name')) {
                    $table->string('first_name');
                }
                if (!Schema::hasColumn('invites', 'last_name')) {
                    $table->string('last_name');
                }
                if (!Schema::hasColumn('invites', 'email')) {
                    $table->string('email')->nullable();
                }
                if (!Schema::hasColumn('invites', 'cell_number')) {
                    $table->string('cell_number');
                }
                if (!Schema::hasColumn('invites', 'invited_by')) {
                    $table->unsignedBigInteger('invited_by');
                }
                if (!Schema::hasColumn('invites', 'token')) {
                    $table->string('token')->unique();
                }
                if (!Schema::hasColumn('invites', 'accepted')) {
                    $table->boolean('accepted')->default(false);
                }
                if (!Schema::hasColumn('invites', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ Only drop the table if it exists (prevent accidental data loss)
        if (Schema::hasTable('invites')) {
            Schema::dropIfExists('invites');
        }
    }
};

