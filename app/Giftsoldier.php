<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Giftsoldier extends Model 
{
	public function memberDetails()
    {
        return $this->belongsTo(mobileapp::class , 'userid','id')->select(['id', 'email','full_name', 'firstname','lastname', 'mobile_number','gender', 'age','dateofbirth', 'marital_status','children', 'education','military_status','employment','list_of_executors','package','loginstatus']);
    }
}
