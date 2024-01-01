<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SettingRequest;
use Illuminate\Http\RedirectResponse;
use File;
use App\ApiSetting;
class ApiSettingController extends CommonController {
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
	}
	public function index(){
		 return redirect(route('edit.apisetting'));
	}
	public function edit(){
		$setting_list = ApiSetting::first();
		$return_data = array();
        $return_data['setting'] = $setting_list;
		$return_data['page_condition'] = 'apisetting_page';
		$return_data['site_title'] = trans('API Settings') . ' | ' . $this->data['site_title'];
		return view('backend/apisetting/create', array_merge($this->data, $return_data));
	}
	public function update(Request $request){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("API Settings Updated" ,$user_id, $_REQUEST,$class);
		$setting = ApiSetting::first();
		if ($request->file('giftsoldier_video')) {
			$image = $request->file('giftsoldier_video');
			$org_name = $image->getClientOriginalName();
			$imageName = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = $this->current_volume_path_upload;
			$docuemntfileurl = '/' . $destinationPath . '/' . $imageName;
			$mv = $image->move($destinationPath,  $imageName);
			//remove old one if exist
			if (trim($setting->giftsoldier_video) != '') {
				@unlink($setting->giftsoldier_video);
			}
			$setting->giftsoldier_video = $docuemntfileurl;
			$setting->save();
		}
		return redirect(route('edit.apisetting'))->with('success_message', trans('Updated Successfully'));
	}
}

