<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageTaggedUser extends Model
{
	protected $table = 'message_tagged_users';
	public function users()
	{
		return $this->hasMany(mobileapp::class,  'id', 'userid')->select(['id','pairid', 'email', 'password', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender','age','dateofbirth','marital_status', 'children', 'education', 'military_status', 'employment', 'list_of_executors', 'package', 'short_detail', 'profile_photo']);
	}
}
