<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Roleprivilege extends Model 
{
	public function roledetails()
    {
        return $this->belongsTo(Role::class , 'roleid', 'id');
    } 
}
