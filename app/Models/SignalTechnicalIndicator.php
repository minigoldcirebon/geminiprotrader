<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalTechnicalIndicator extends Model
{
    protected $fillable = [
        'expert_signal_id',
        'indicator_name',
        'value',
        'signal_line_value',
        'status',
        'strength'
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'signal_line_value' => 'decimal:4',
        'strength' => 'integer'
    ];

    public function expertSignal()
    {
        return $this->belongsTo(ExpertSignal::class);
    }
}
