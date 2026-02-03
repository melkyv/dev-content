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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('cancel_at')->nullable()->after('ends_at');
            $table->boolean('cancel_at_period_end')->default(false)->after('cancel_at');
            $table->timestamp('canceled_at')->nullable()->after('cancel_at_period_end');
            $table->string('currency', 3)->default('brl')->after('canceled_at');
            $table->integer('amount')->default(0)->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['cancel_at', 'canceled_at', 'currency', 'amount', 'cancel_at_period_end']);
        });
    }
};
