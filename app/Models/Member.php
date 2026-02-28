<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'alternate_phone',
        'date_of_birth', 'gender', 'marital_status', 'address', 'city',
        'state', 'zip_code', 'country', 'emergency_contact_name',
        'emergency_contact_phone', 'emergency_contact_relationship',
        'profile_photo', 'membership_status', 'membership_date',
        'baptism_date', 'wedding_anniversary', 'occupation', 'employer',
        'notes', 'family_id', 'family_role', 'membership_class',
        'qr_code', 'created_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'baptism_date' => 'date',
        'wedding_anniversary' => 'date',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function documents()
    {
        return $this->hasMany(MemberDocument::class);
    }

    public function clusters()
    {
        return $this->belongsToMany(Cluster::class, 'cluster_members')
                    ->withPivot('role', 'joined_date')
                    ->withTimestamps();
    }

    public function prayerRequests()
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function clusterFollowups()
    {
        return $this->hasMany(ClusterFollowup::class);
    }
}
