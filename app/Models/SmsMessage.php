<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'recipient_type', 'recipients', 'message',
        'template_id', 'scheduled_at', 'sent_at', 'status',
        'delivery_report', 'total_recipients', 'delivered_count', 'failed_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function template()
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }
}
