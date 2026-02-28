<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'content', 'category',
    ];

    public function messages()
    {
        return $this->hasMany(SmsMessage::class, 'template_id');
    }
}
