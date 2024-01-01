<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model 
{
   	public function course()
    {
        return $this->hasMany(Course::class , 'TutorId','id');
    }
    public function buyCorsedetails()
    {
    	return $this->hasMany(BuyCourse::class , 'studentId','id');
    }
}
