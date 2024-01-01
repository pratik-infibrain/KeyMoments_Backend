<?php namespace App\Http\Requests;
use Auth;
use App\Package;
use App\Http\Requests\Request;
class PackageRequest extends Request 
{
	public function authorize()
	{
		return true;
	}
	public function rules(){
		$package_name = $this->package_name;
		if($this->route('id') != ''){
			$check_data = Package::where('package_name','=',$package_name)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = Package::where('package_name','=',$package_name)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'package_name' => 'required|max:255',
				'package_price' => 'required',
				'number_of_notes' => 'required|max:11',
				'number_of_photos' => 'required|max:11',
				'number_of_videos' => 'required|max:11',
				'data_limit' => 'required|max:255',

				
			];
		}else{
			return [
				'package_name' => 'required|max:255|unique:packages,package_name,'.$this->route('id'),
				'package_price' => 'required',
				'number_of_notes' => 'required',
				'number_of_photos' => 'required',
				'number_of_videos' => 'required',
				'data_limit' => 'required',
				
			];
			
		}
	}
	public function messages(){
		return [
			'package_name.required' => 'package name is required.',
			'package_name.unique' => 'package name already exits.',
			'package_price.required' => 'Package Price is required.',
			'number_of_notes.required' => 'Number Of Notes is required.',
			'number_of_photos.required' => 'Number Of Photos is required.',		
			'number_of_videos.required' => 'Number Of Videos is required.',		
			'data_limit.required' => 'Data Limit is required.',		
		];
    }
}
