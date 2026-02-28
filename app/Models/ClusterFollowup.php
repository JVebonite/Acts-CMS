<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusterFollowup extends Model
{
    use HasFactory;

    protected $table = 'cluster_followups';

    protected $fillable = [
        'cluster_id', 'member_id', 'follow_up_by', 'follow_up_date',
        'type', 'notes', 'status',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function followUpPerson()
    {
        return $this->belongsTo(Member::class, 'follow_up_by');
    }
}
