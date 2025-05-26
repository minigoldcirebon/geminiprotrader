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
        Schema::table('expert_signals', function (Blueprint $table) {
            $table->integer('overall_strength')->default(50); // 0-100 overall signal strength
            $table->string('current_trend')->default('NEUTRAL'); // BEARISH, BULLISH, NEUTRAL
            $table->text('analysis_summary')->nullable(); // Analysis description
            $table->string('provider_name')->nullable(); // Signal provider name
            $table->string('chart_symbol')->nullable(); // Chart symbol like XAUUSD
            $table->decimal('current_price', 10, 5)->nullable(); // Current market price
            $table->string('market_status')->default('OPEN'); // OPEN, CLOSED
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expert_signals', function (Blueprint $table) {
            $table->dropColumn([
                'overall_strength',
                'current_trend', 
                'analysis_summary',
                'provider_name',
                'chart_symbol',
                'current_price',
                'market_status'
            ]);
        });
    }
};
