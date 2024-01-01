<?php namespace App\Http\Requests;
use Auth;
use App\mobileapp;
use App\Http\Requests\Request;
class mobileappRequest extends Request
{
	public function authorize()
	{
		return true;
	}
	public function rules(){
		$email = $this->email;
		$return = [];
		if($this->route('id') != ''){
			$check_data = mobileapp::where('email','=',$email)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$return = [
				'password' => 'required|max:255',
				'cpassword' => 'required|max:255'
			];
			$check_data = mobileapp::where('email','=',$email)->where('deleted','=','0')->count();
		}

		if (!$email && $this->route('id') != '') {
			return [
				'password' => 'required|max:255',
				'cpassword' => 'required_with:password|same:password'
			];
		}
		if($check_data == 0){
			return $return + [
				'email' => 'required|max:255',
				'full_name' => 'required|max:255',
				'mobile_number' => 'required|max:11',
				'gender' => 'required',
				'age' => 'required|integer|between:1,150',
				'marital_status' => 'required',
				#'children' => 'required|integer|between:1,20',
				'education' => 'required|max:255',
				'military_status' => 'required',
				'employment' => 'required|max:255',
				'list_of_executors' => 'required|max:255',
				'package' => 'required|max:255',


			];
		}else{
			return $return + [
				'email' => 'required|max:255|unique:mobileapps,email,'.$this->route('id'),
				'full_name' => 'required|max:255',
				'mobile_number' => 'required|max:11',
				'gender' => 'required',
				'age' => 'required|integer|between:1,150',
				'marital_status' => 'required',
			#	'children' => 'required|integer|between:1,20',
				'education' => 'required|max:255',
				'military_status' => 'required',
				'employment' => 'required|max:255',
				'list_of_executors' => 'required|max:255',
				'package' => 'required|max:255',

			];

		}
	}
	public function messages(){
		return [
			'email.required' => 'Email is required.',
			'email.unique' => 'Email already exits.',
			'password.required' => 'Password is required.',
			'cpassword.required' => 'Confirmation password is required.',
			'full_name.required' => 'Full Name is required.',
			'mobile_number.required' => 'Mobile number is required.',
			'gender.required' => 'Gender is required.',
			'age.required' => 'Age is required.',
			'age.between' => 'Age must be between 1 and 150.',
			'marital_status.required' => 'Marital Status is required.',
			'children.required' => 'Children is required.',
			'children.between' => 'Children must be between 1 and 20',
			'education.required' => 'Education is required.',
			'military_status.required' => 'Military Status is required.',
			'employment.required' => 'Employment is required.',
			'list_of_executors.required' => 'List Of Executors is required.',
			'package.required' => 'Package is required.',
		];
    }
}
