<?php namespace App\Http\Requests;
use Auth;
use App\User;
use App\Http\Requests\Request;

class AdminUserRequest extends Request 
{
	public function authorize()
	{
		return true;
	}
	public function rules()
	{
		$email = $this->email;
		if($this->route('id') != '')
		{
			$check_data = User::where('email','=',$email)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}
		else
		{
			$check_data = User::where('email','=',$email)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			if($this->route('id') == '')
			{
				return [
					//'name' => 'required|max:255|unique:users,name',
					'email' => 'required|email|max:255|unique:users,email',
					'password' => 'required|min:6|max:255',
					'cpassword' => 'required|min:6|max:255',
					'firstname' => 'required',
					'lastname' => 'required',
					'phone' => 'required',
					'role_id' => 'required'
				];
			}
			else
			{
				return [
					//'name' => 'required|max:255|unique:users,name,'.$this->route('id'),
					'email' => 'required|email|max:255|unique:users,email,'.$this->route('id'),
					'firstname' => 'required',
					'lastname' => 'required',
					'phone' => 'required'
				];
			}	
		}
		else
		{
			return [
				//'name' => 'required|max:255|unique:users,name,'.$this->route('id'),
				'email' => 'required|max:255|email|unique:users,email,'.$this->route('id'),
				'firstname' => 'required',
				'lastname' => 'required',
				'phone' => 'required'
			];
		}
	}  
	public function messages(){
		return [
		    //'name.required' => 'Username is required.',
		    'firstname.required' => 'First name is required.',
		    'lastname.required' => 'Last name is required.',
		    'phone.required' => 'Phone is required.',
//'name.unique' => 'Username already exits.',
		    'email.required' => 'Email is required.',
			'email.email' => 'Email must be a valid email.',
			'email.unique' => 'Email already exits.',
			'password.required' => 'Password is required.',
			'image.mimes' => 'Image is required.',
			'role_id.required' => 'Role is required.',
			'cpassword.required' => 'Confirm password is required.'
		];
    }
}
