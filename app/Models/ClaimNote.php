<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'admin_user_id',
        'note_content',
        'edited_by',
        'edited_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    /**
     * Get the claim that owns this note.
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the admin user who created this note.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * Get the admin user who last edited this note.
     */
    public function editedByUser()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    /**
     * Check if this note was edited.
     */
    public function wasEdited()
    {
        return !is_null($this->edited_at);
    }

    /**
     * Get formatted timestamp for display.
     */
    public function getFormattedTimestamp()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Get formatted edited timestamp for display.
     */
    public function getFormattedEditedTimestamp()
    {
        return $this->edited_at ? $this->edited_at->format('M d, Y h:i A') : null;
    }
}
