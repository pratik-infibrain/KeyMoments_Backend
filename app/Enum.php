<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Enum extends Model 
{
	public function parentdetails()
    {
        return $this->belongsTo(Enum::class , 'parentname', 'id');
    } 
}
