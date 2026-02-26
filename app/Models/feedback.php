<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
    'ticket_id',
    'speed_rating',
    'waiting_rating',
    'solution_rating',
    'comment',
];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}