<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Userpackage extends Model 
{
	public function packages()
	{
		return $this->belongsTo(Package::class, 'packageid', 'id');
	}
	
	public function user()
	{
		return $this->belongsTo(mobileapp::class, 'userid', 'id');
	}
}
