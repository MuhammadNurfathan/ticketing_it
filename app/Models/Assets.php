<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assets';

    protected $fillable = [
        'assets_code',
        'assets_name',
        'image',
        'category',
        'status',
        'model',
        'check_in',
        'check_out',
        'check_out_to',
        'location',
        'notes',
    ];
}
