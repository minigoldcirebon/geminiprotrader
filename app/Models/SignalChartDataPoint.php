<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalChartDataPoint extends Model
{
    protected $fillable = [
        'expert_signal_id',
        'timeframe',
        'timestamp',
        'open',
        'high', 
        'low',
        'close',
        'volume',
        'rsi_value',
        'macd_value',
        'macd_signal'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'open' => 'decimal:5',
        'high' => 'decimal:5',
        'low' => 'decimal:5', 
        'close' => 'decimal:5',
        'volume' => 'integer',
        'rsi_value' => 'decimal:2',
        'macd_value' => 'decimal:5',
        'macd_signal' => 'decimal:5'
    ];

    public function expertSignal()
    {
        return $this->belongsTo(ExpertSignal::class);
    }
}
