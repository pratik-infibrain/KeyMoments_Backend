<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationAlert extends Model {
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notification_alert';

    public function userdetails()
    {
        return $this->belongsTo(mobileapp::class , 'userid', 'id');
    } 
}