<?php namespace App\Http\Requests;
use Auth;
use App\Role;
use App\Http\Requests\Request;
class RoleRequest extends Request {
	public function authorize(){
		return true;
	}
	public function rules(){
		$name = $this->name;
		if($this->route('id') != ''){
			$check_data = Role::where('name','=',$name)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = Role::where('name','=',$name)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'name' => 'required|max:255',
			];
		}else{
			return [
				'name' => 'required|max:255|unique:roles,name,'.$this->route('id'),
			];
			
		}
	}
	public function messages(){
		return [
			'name.required' => 'Name is required.',
			'name.unique' => 'Name is already exits.'
		];
    }
}
