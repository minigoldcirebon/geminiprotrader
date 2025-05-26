<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expert_signals', function (Blueprint $table) {
            // Performance tracking fields
            $table->decimal('actual_close_price', 15, 8)->nullable()->after('stop_loss');
            $table->decimal('profit_loss_percentage', 8, 4)->nullable()->after('actual_close_price');
            $table->enum('signal_result', ['profit', 'loss', 'pending', 'expired'])->default('pending')->after('profit_loss_percentage');
            $table->timestamp('closed_at')->nullable()->after('signal_result');
            
            // Display enhancement fields
            $table->integer('priority')->default(0)->after('closed_at');
            $table->integer('confidence_level')->default(3)->after('priority'); // 1-5 scale
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('medium')->after('confidence_level');
            $table->string('image_url')->nullable()->after('risk_level');
            $table->string('timeframe')->nullable()->after('image_url'); // 1h, 4h, 1d, etc
            
            // Webhook integration fields
            $table->string('external_id')->nullable()->after('timeframe');
            $table->string('webhook_source')->nullable()->after('external_id'); // tradingview, mt4, manual
            $table->json('webhook_payload')->nullable()->after('webhook_source');
            
            // Add indexes for performance
            $table->index('signal_result');
            $table->index('webhook_source');
            $table->index('priority');
            $table->index('confidence_level');
        });
    }

    public function down(): void
    {
        Schema::table('expert_signals', function (Blueprint $table) {
            $table->dropColumn([
                'actual_close_price',
                'profit_loss_percentage', 
                'signal_result',
                'closed_at',
                'priority',
                'confidence_level',
                'risk_level',
                'image_url',
                'timeframe',
                'external_id',
                'webhook_source',
                'webhook_payload'
            ]);
        });
    }
};
