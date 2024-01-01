<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SettingRequest;
use Illuminate\Http\RedirectResponse;
use File;
class SettingController extends CommonController {
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
	}
	public function index(){
		 return redirect(route('edit.setting'));
	}
	public function edit(){
		$setting_list = Settings::where('deleted','=','0')->orderBy('id', 'ASC')->get();
		$return_data = array();
        $return_data['setting_list'] = $setting_list;
		$return_data['page_condition'] = 'setting_page';
		$return_data['site_title'] = trans('Settings') . ' | ' . $this->data['site_title'];
		return view('backend/setting/create', array_merge($this->data, $return_data));
	}
	public function update(SettingRequest $request){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Settings Updated" ,$user_id, $_REQUEST,$class);
		$setting_list = Settings::where('deleted','=','0')->orderBy('id', 'ASC')->get();
		$return_data = array();
		foreach($setting_list as $setting_list_val){
			echo $this->slugify($setting_list_val->setting_key).'<br>';
		} 
		return redirect(route('edit.setting'))->with('success_message', trans('Updated Successfully'));
	}
}
