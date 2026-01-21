<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('disk')->default('local');
            $table->boolean('is_processed')->default(false);
            $table->timestamps();

            $table->index('content_id');
            $table->index('disk');
            $table->index('is_processed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
