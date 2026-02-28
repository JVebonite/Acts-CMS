<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'service_date', 'service_time', 'type', 'notes',
    ];

    protected $casts = [
        'service_date' => 'date',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
