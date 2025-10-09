<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemCategory extends Model
{
    protected $table = 'problem_categories';
    protected $fillable = ['problem_category_name'];
}
