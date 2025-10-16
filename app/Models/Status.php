<?php
namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class Status extends Model
{
    use SoftDeletes;
    protected $table = 'status';
    protected $fillable = ['status_name'];
    public $timestamps =    true;
}
