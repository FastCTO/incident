<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incident_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('uploaded_by')->nullable();

            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('file_type')->nullable();
            $table->string('sha256_hash', 64)->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('incident_id');
            $table->index('uploaded_by');
            $table->index('file_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_files');
    }
};
