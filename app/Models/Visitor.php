<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address',
        'visit_date', 'invited_by', 'service_attended', 'follow_up_status',
        'follow_up_notes', 'converted_to_member', 'member_id', 'notes',
        'prayer_request',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'converted_to_member' => 'boolean',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
