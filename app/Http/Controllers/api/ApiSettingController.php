<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Promotion;
use App\Package;
use App\ApiSetting;
use App\Giftsoldier;
use File,DB;

class ApiSettingController extends CommonController
{
    public function countpages($path) 
    { 
      $pdf = file_get_contents($path); 
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy); 
      return $number; 
    }  
    public function getApiSetting()
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        // $this->current_volume_path_upload
        $setting = ApiSetting::select(DB::raw("CONCAT('".url()."',giftsoldier_video) AS giftsoldier_video", 'id'))->first();
        if(sizeof($setting) > 0):
            $response['success'] = true;
            $response['message'] = 'Settings get successfully.';
            if($setting):
            
            endif; 
            $response['data']->settingDetails = $setting;
        else:
            $response['success'] = false;
            $response['message'] = 'no records found.';
        endif;    
        return response($response, 200);
    }
  
}   
?>