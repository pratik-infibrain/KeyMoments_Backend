<?php namespace App\Http\Requests;
use Auth;
use App\Pages;
use App\Http\Requests\Request;
class PageRequest extends Request {
	public function authorize(){
		return true;
	}
	public function rules(){
		$menu_title = $this->menu_title;
		if($this->route('id') != ''){
			$check_data = Pages::where('menu_title','=',$menu_title)->where('id','!=',$this->route('id'))->where('deleted','=','0')->count();
		}else{
			$check_data = Pages::where('menu_title','=',$menu_title)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'page_title' => 'required|max:255',
				'menu_title' => 'required|max:255',
				'description' => 'required',
			];	
		}else{
			return [
				'page_title' => 'required|max:255',
				'menu_title' => 'required|max:255|unique:pages,menu_title,'.$this->route('id'),
				'description' => 'required',
			];
		}
	}
}
