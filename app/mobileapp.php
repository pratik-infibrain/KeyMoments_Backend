<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class mobileapp extends Model 
{
    public function childrens()
    {
        return $this->hasMany(Childrendetail::class,  'userid', 'id');
    }
    
    public function executors()
    {
        return $this->hasMany(userexicuter::class,  'user_id', 'id');
    }
    
    public function packageDetails() {
    	return $this->hasOne(Userpackage::class, 'userid', 'id')->select('id', 'userid', 'packageid', 'packageprice')->orderBy('id', 'DESC');
    }
    
    public function giftPriceDetails() {
    	return $this->hasOne(Giftsoldier::class, 'userid', 'id')->select('id', 'userid', 'giftprice');
    }
    
    public function userSetting() {
    	return $this->hasOne(User_setting::class, 'user_id', 'id')->select('id', 'user_id', 'data');
    }
}
 