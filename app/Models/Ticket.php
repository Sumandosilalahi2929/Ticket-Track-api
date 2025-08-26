<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'title',
        'description',
        'priority',
        'status'
    ];

    public function replies()
    {
        return $this->hasMany(TicketReplay::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
