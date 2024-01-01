<?php namespace App\Http\Requests;
use Auth;
use App\Notification;
use App\Http\Requests\Request;
class NotificationRequest extends Request
{
	public function authorize()
	{
		return true;
	}
	public function rules(){
		//$package_name = $this->package_name;
		if($this->route('id') != ''){
			$check_data = Notification::where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = Notification::where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'notification_title' => 'required|max:255',
				'notification_content' => 'required|max:255',
				//'notification_img' => 'required',


			];
		}else{
			return [
				'notification_title' => 'required|max:255',
				'notification_content' => 'required|max:255',
				//'notification_img' => 'required',

			];

		}
	}
	public function messages(){
		return [
			'notification_title.required' => 'Notification Title is required.',
			'notification_content.required' => 'Notification Content is required.',
			//'notification_img.required' => 'Notification Image is required.',

		];
    }
}
