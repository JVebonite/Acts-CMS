<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'title', 'file_path', 'file_type', 'category',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
