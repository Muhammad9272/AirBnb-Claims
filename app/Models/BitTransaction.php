<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'balance_after',
        'source_type',
        'source_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSourceAttribute()
    {
        if ($this->source_type === 'task' && $this->source_id) {
            return BitTask::find($this->source_id);
        } elseif ($this->source_type === 'order' && $this->source_id) {
            return Order::find($this->source_id);
        }
        
        return null;
    }
}
