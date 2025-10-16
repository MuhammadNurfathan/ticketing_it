<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProblemCategory extends Model
{
    use SoftDeletes;
    protected $table = 'problem_categories';
    protected $fillable = ['problem_category_name'];
}
