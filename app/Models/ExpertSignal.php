<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertSignal extends Model
{
    use HasFactory;    protected $fillable = [
        'pair',
        'signal_type',
        'entry_price',
        'take_profit',
        'stop_loss',
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
        'webhook_payload',
        'analysis_reason',
        'status',
        'created_by',
        'approved_by',
        'published_at',
        'expires_at',
        'metadata',
        'overall_strength',
        'current_trend',
        'analysis_summary',
        'provider_name',
        'chart_symbol',
        'current_price',
        'market_status',
    ];    protected $casts = [
        'entry_price' => 'decimal:8',
        'take_profit' => 'decimal:8',
        'stop_loss' => 'decimal:8',
        'actual_close_price' => 'decimal:8',
        'profit_loss_percentage' => 'decimal:4',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'closed_at' => 'datetime',
        'metadata' => 'array',
        'webhook_payload' => 'array',
        'overall_strength' => 'integer',
        'current_price' => 'decimal:5',
    ];

    /**
     * Get the user who created this signal.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this signal.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get technical indicators for this signal.
     */
    public function technicalIndicators()
    {
        return $this->hasMany(SignalTechnicalIndicator::class);
    }

    /**
     * Get chart data points for this signal.
     */
    public function chartDataPoints()
    {
        return $this->hasMany(SignalChartDataPoint::class);
    }

    /**
     * Scope for published signals.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for pending signals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for active signals (published and not expired).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Check if signal is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at < now();
    }

    /**
     * Approve the signal.
     */
    public function approve(User $approver): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
        ]);
    }

    /**
     * Publish the signal.
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Reject the signal.
     */
    public function reject(): void
    {
        $this->update([
            'status' => 'rejected',
        ]);
    }

    /**
     * Get formatted entry price.
     */
    public function getFormattedEntryPriceAttribute(): string
    {
        return number_format($this->entry_price, 8);
    }

    /**
     * Get signal type badge color.
     */
    public function getSignalTypeBadgeColorAttribute(): string
    {
        return match($this->signal_type) {
            'BUY' => 'green',
            'SELL' => 'red',
            'HODL' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Calculate profit/loss percentage based on signal type and close price.
     */
    public function calculateProfitLoss(float $closePrice): float
    {
        if (!$this->entry_price || $closePrice <= 0) {
            return 0;
        }

        if ($this->signal_type === 'BUY') {
            return (($closePrice - $this->entry_price) / $this->entry_price) * 100;
        } elseif ($this->signal_type === 'SELL') {
            return (($this->entry_price - $closePrice) / $this->entry_price) * 100;
        }

        return 0;
    }

    /**
     * Close the signal with actual price and calculate result.
     */
    public function closeSignal(float $closePrice): void
    {
        $profitLoss = $this->calculateProfitLoss($closePrice);
        $result = $profitLoss > 0 ? 'profit' : 'loss';

        $this->update([
            'actual_close_price' => $closePrice,
            'profit_loss_percentage' => $profitLoss,
            'signal_result' => $result,
            'closed_at' => now(),
        ]);
    }

    /**
     * Scope for webhook-created signals.
     */
    public function scopeFromWebhook($query)
    {
        return $query->whereNotNull('webhook_source');
    }

    /**
     * Scope for profitable signals.
     */
    public function scopeProfitable($query)
    {
        return $query->where('signal_result', 'profit');
    }

    /**
     * Scope for signals by confidence level.
     */
    public function scopeByConfidence($query, int $level)
    {
        return $query->where('confidence_level', $level);
    }

    /**
     * Get confidence level badge color.
     */
    public function getConfidenceBadgeColorAttribute(): string
    {
        return match($this->confidence_level) {
            1, 2 => 'red',
            3 => 'yellow',
            4, 5 => 'green',
            default => 'gray'
        };
    }

    /**
     * Get risk level badge color.
     */
    public function getRiskBadgeColorAttribute(): string
    {
        return match($this->risk_level) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get signal result badge color.
     */
    public function getResultBadgeColorAttribute(): string
    {
        return match($this->signal_result) {
            'profit' => 'green',
            'loss' => 'red',
            'pending' => 'blue',
            'expired' => 'gray',
            default => 'gray'
        };
    }
}
