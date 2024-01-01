<?php namespace App\Http\Requests;
use Auth;
use App\Http\Requests\Request;
class SettingRequest extends Request {
	public function authorize(){
		return true;
	}
	public function rules(){
		return [
			'common_setting' => 'required',
		];
	}

}
