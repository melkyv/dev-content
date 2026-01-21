<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->date('date')->index();
            $table->timestamps();

            $table->unique(['content_id', 'date']);
            $table->index(['date', 'views']);
            $table->index(['date', 'downloads']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_metrics');
    }
};
