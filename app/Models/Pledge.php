<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'campaign_id', 'amount_pledged', 'amount_fulfilled',
        'start_date', 'end_date', 'frequency', 'status', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_pledged' => 'decimal:2',
        'amount_fulfilled' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getFulfillmentPercentageAttribute()
    {
        if ($this->amount_pledged <= 0) return 0;
        return min(100, round(($this->amount_fulfilled / $this->amount_pledged) * 100, 1));
    }
}
