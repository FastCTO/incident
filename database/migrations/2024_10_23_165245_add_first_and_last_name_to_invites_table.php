<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
    o */
       public function up(): void
    {
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
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            //
        });
    }
};
