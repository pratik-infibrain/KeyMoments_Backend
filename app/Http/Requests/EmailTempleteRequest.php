<?php namespace App\Http\Requests;
use Auth;
use App\EmailTemplete;
use App\Http\Requests\Request;
class EmailTempleteRequest extends Request {
	public function authorize(){
		return true;
	}
	public function rules(){
		$name = $this->name;
		if($this->route('id') != ''){
			$check_data = EmailTemplete::where('name','=',$name)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = EmailTemplete::where('name','=',$name)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'name' => 'required|max:255',
				'website_id' => 'required|max:11',
				'description' => 'required',
			];
		}else{
			return [
				'name' => 'required|max:255|unique:email_templetes,name,'.$this->route('id'),
				'website_id' => 'required|max:11',
				'description' => 'required',
			];
			
		}
	}
	public function messages(){
		return [
			'website_id.required' => 'The Website field is required.',
		];
    }
}
