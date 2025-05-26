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
        Schema::create('signal_technical_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_signal_id')->constrained('expert_signals')->onDelete('cascade');
            $table->string('indicator_name'); // RSI, MACD, STOCH, CCI, ADX, WILLIAMS
            $table->decimal('value', 10, 4); // Indicator value
            $table->decimal('signal_line_value', 10, 4)->nullable(); // For indicators like MACD that have signal lines
            $table->string('status')->default('neutral'); // bullish, bearish, neutral
            $table->integer('strength')->default(50); // 0-100 strength percentage
            $table->timestamps();
            
            $table->index(['expert_signal_id', 'indicator_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signal_technical_indicators');
    }
};
