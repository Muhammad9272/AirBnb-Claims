<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'sender_id', 'sender_type', 'message', 'attachments'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    // A reply belongs to a ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get the sender (polymorphic relationship)
    public function sender()
    {
        return $this->sender_type === 'admin' 
            ? $this->belongsTo(Admin::class, 'sender_id')
            : $this->belongsTo(User::class, 'sender_id');
    }

    // Helper method to get attachment URL
    public function getAttachmentUrl($filename)
    {
        return asset('storage/tickets/' . $filename);
    }

    // Helper to check if the reply is from admin
    public function isFromAdmin()
    {
        return $this->sender_type === 'admin';
    }

    // Helper to check if the reply is from user
    public function isFromUser()
    {
        return $this->sender_type === 'user';
    }
}
