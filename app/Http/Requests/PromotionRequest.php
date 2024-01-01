<?php namespace App\Http\Requests;
use Auth;
use App\Promotion;
use App\Http\Requests\Request;
class PromotionRequest extends Request 
{
	public function authorize()
	{
		return true;
	}
	public function rules(){
		//$package_name = $this->package_name;
		if($this->route('id') != ''){
			$check_data = Promotion::where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = Promotion::where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'promotion_code' => 'required|max:255',
				//'promotion_content' => 'required|max:255',
				'valid_form_date' => 'required',
				'valid_to_date' => 'required',
				'type' => 'required|max:255',
				'value_percentage' => 'required',
				
			];
		}else{
			return [
				'promotion_code' => 'required|max:255',
				//'promotion_content' => 'required|max:255',
				'valid_form_date' => 'required',
				'valid_to_date' => 'required',
				'type' => 'required|max:255',
				'value_percentage' => 'required',
				
			];
			
		}
	}
	public function messages(){
		return [
			'promotion_code.required' => 'Promotion code is required.',
			//'promotion_content.required' => 'Description is required.',
			'valid_form_date.required' => 'Valid from date is required.',
			'valid_to_date.required' => 'Valid to date is required.',
			'type.required' => 'Type is required.',
			'value_percentage.required' => 'Value is required.',
			
				
		];
    }
}
