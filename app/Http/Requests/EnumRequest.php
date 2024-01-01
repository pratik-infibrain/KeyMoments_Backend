<?php namespace App\Http\Requests;
use Auth;
use App\Enum;
use App\Http\Requests\Request;
class EnumRequest extends Request 
{
	public function authorize()
	{
		return true;
	}
	public function rules(){
		$enumname = $this->enumname;
		if($this->route('id') != ''){
			$check_data = Enum::where('enumname','=',$enumname)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = Enum::where('enumname','=',$enumname)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'enumname' => 'required|max:255',
				'enumvalue' => 'required|max:255',
				//'parentname' => 'required|max:255',
				//'parentvalue' => 'required|max:255|regex:/^\d+(\.\d{1,2})?$/'
			];
		}else{
			return [
				'enumname' => 'required|max:255|unique:enums,enumname,'.$this->route('id'),
				'enumvalue' => 'required|max:255',
				//'parentname' => 'required|max:255',
				//'parentvalue' => 'required|max:255|regex:/^\d+(\.\d{1,2})?$/'
			];
			
		}
	}
	public function messages(){
		return [
			'enumname.required' => 'Enum name is required.',
			'enumname.unique' => 'Enum name already exits.',
			'enumvalue.required' => 'Enum value is required.',
			'parentname.required' => 'Parent name is required.',
			'parentvalue.required' => 'Parent value is required.'		
		];
    }
}
