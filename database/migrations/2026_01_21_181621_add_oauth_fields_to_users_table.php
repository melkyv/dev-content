<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('avatar_path')->nullable()->after('email_verified_at');
            $table->string('provider')->nullable()->after('avatar_path');
            $table->string('provider_id')->nullable()->after('provider');

            $table->index(['provider', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['provider', 'provider_id']);
            $table->dropColumn(['avatar_path', 'provider', 'provider_id']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
