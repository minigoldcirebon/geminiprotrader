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
        Schema::create('signal_chart_data_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_signal_id')->constrained('expert_signals')->onDelete('cascade');
            $table->string('timeframe'); // M1, M5, M15, M30, H1, H4, D1
            $table->datetime('timestamp');
            $table->decimal('open', 10, 5);
            $table->decimal('high', 10, 5);
            $table->decimal('low', 10, 5);
            $table->decimal('close', 10, 5);
            $table->bigInteger('volume')->nullable();
            $table->decimal('rsi_value', 5, 2)->nullable(); // RSI value at this point
            $table->decimal('macd_value', 10, 5)->nullable(); // MACD value
            $table->decimal('macd_signal', 10, 5)->nullable(); // MACD signal line
            $table->timestamps();
            
            $table->index(['expert_signal_id', 'timeframe', 'timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signal_chart_data_points');
    }
};
