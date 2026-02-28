<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'target_amount', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_amount' => 'decimal:2',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amount');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->total_donations / $this->target_amount) * 100, 1));
    }
}
