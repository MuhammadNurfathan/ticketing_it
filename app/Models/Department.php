<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    use SoftDeletes;
    protected $table ='departments';
    protected $primaryKey = 'id';
    protected $fillable =['location_id','department_name'];

    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
}
