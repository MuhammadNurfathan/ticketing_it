<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table ='departments';
    protected $primaryKey = 'id';
    protected $fillable =['location_id','department_name'];

    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
}
