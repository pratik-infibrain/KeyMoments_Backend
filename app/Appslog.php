<?php namespace App;
use Illuminate\Database\Eloquent\Model;
class Appslog extends Model
{
	protected $guarded   = array('id');
    public static $rules = array(
        'name' => 'required',
    );
    public function getCreatedAtAttribute($value)
    {
    	return Carbon::createFromFormat("Y-m-d H:i:s",$value)->format('d-m-Y H:i:s');
    }
}
