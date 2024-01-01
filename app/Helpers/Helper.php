<?php 
namespace App\Helpers;
use Auth;
use Session;
use DateTime;
class Helper {
	public static function getAccessDetail($access_type){ 
		$login_user_role = 0;
		$login_user = Auth::user();
		if(count($login_user) != 0){
			$login_user_role = $login_user->role_id;
		}
		$user_access = 0;
		$user_role_check = \App\UserRole::where('role_id','=',$login_user_role)->where($access_type,'=',1)->first();
		if(count($user_role_check) != 0){
			$user_access = 1;
		}
		return $user_access;
	}
	public static function GetName($action, $match_value){
		$select_data = '';;
		if($action == 'view_date_format'){
            if($match_value != ""){
                if($match_value != "0000-00-00"){
        			$date_start_date_array_value = date('Y-m-d',strtotime($match_value));
                    if($date_start_date_array_value != '1970-01-01'){
        				$select_data = date('d/m/Y',strtotime($match_value));
        			}
                }    
            }    
		}elseif($action == 'insert_date_format'){
            $select_data = "";
                $select_date_array = explode('/',$match_value);
                $date_confirm_date_array_value = "";
                if(count($select_date_array) == 3){
                    $start_date_array_value =  $select_date_array[2].'-'.$select_date_array[1].'-'.$select_date_array[0];
                    $date_confirm_date_array_value = date('Y-m-d',strtotime($start_date_array_value));
                    if($date_confirm_date_array_value != '1970-01-01'){
                        $select_data = date('Y/m/d',strtotime($start_date_array_value));
                    }
                }
        }elseif($action == 'branch_range_number'){
            $get_detail = \App\BranchRange::select('range_from','range_to')->where('id','=',$match_value)->first();
            if(count($get_detail) != 0){
                $select_data = $get_detail->range_from.' - '.$get_detail->range_to;
            }
        }
		return $select_data;
	}
    public function getmodule()
    {
        $modulelist = \App\Module::where('deleted','0')->get();
        return $modulelist;
    }
    public function getrolepermision(){
        $usersrolse = Auth::user()->role_id;
        $modulelist = \App\Roleprivilege::where('roleid',$usersrolse)->where('deleted','0')->first();
        $moduleidsarray = explode(',',$modulelist->moduleid);
        return $moduleidsarray;
    }
}