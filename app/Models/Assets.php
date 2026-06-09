<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'code',
        'name',
        'image',
        'category',
        'status',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'asset_id');
    }
}