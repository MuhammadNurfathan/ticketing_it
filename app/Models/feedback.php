<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use SoftDeletes;
    protected $fillable = ['ticket_id','description','rating'];
    protected $table = 'feedback';
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class,'ticket_id')->withTrashed();
    }
}
