<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'subject', 'description', 'status', 'ticket_id'
    ];

    // A ticket has many replies
    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    // Optionally, define relationship with user (if using standard User model)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Add this method to generate unique ticket ID
    public static function generateTicketID()
    {
        $prefix = 'TKT';
        $random = strtoupper(Str::random(6));
        $ticketId = $prefix . '-' . $random;

        // Ensure uniqueness
        while (self::where('ticket_id', $ticketId)->exists()) {
            $random = strtoupper(Str::random(6));
            $ticketId = $prefix . '-' . $random;
        }

        return $ticketId;
    }

    // Add route key name for implicit binding
    public function getRouteKeyName()
    {
        return 'ticket_id';
    }
}
