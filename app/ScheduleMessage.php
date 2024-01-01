<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleMessage extends Model
{
	public function media_files()
	{
		return $this->hasMany(MediaFile::class,  'type_id', 'id')->where('type', 'schedule_message');
	}
	public function keymoment() {
		return $this->belongsTo(Keymoment::class, 'key_id', 'id')->select('id', 'title')->where('status', '1')->where('deleted', '0');
	}
	
	public function tagged_users()
	{
		//return $this->hasManyThrough(mobileapp::class, MessageTaggedUser::class, 'userid', 'messageid', 'id');
        
		return $this->hasMany(MessageTaggedUser::class, 'messageid', 'id');
		//return $this->hasMany(MessageTaggedUser::class, 'messageid')         ->leftJoin('mobileapps', 'message_tagged_users.userid', '=', 'mobileapps.id')->select('mobileapps.*');
	}

	public function messages()
	{
		return $this->hasMany(Allmessage::class,  'scheduleid', 'id');
	}
	
}
