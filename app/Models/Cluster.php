<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'leader_id', 'description', 'meeting_day',
        'meeting_time', 'location', 'status',
    ];

    public function leader()
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'cluster_members')
                    ->withPivot('role', 'joined_date')
                    ->withTimestamps();
    }

    public function followups()
    {
        return $this->hasMany(ClusterFollowup::class);
    }
}
