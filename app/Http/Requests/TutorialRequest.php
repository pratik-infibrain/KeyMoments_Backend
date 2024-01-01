<?php namespace App\Http\Requests;
use Auth;
use App\Tutorial;
use App\Http\Requests\Request;
class TutorialRequest extends Request 
{
	public function authorize()
	{
		return true;
	}
	public function rules(){
		$tutorialname = $this->tutorialname;
		if($this->route('id') != ''){
			$check_data = Tutorial::where('tutorialname','=',$tutorialname)->where('id','=',$this->route('id'))->where('deleted','=','0')->count();
			
		}else{
			$check_data = Tutorial::where('tutorialname','=',$tutorialname)->where('deleted','=','0')->count();
		}
		if($check_data == 0){
			return [
				'tutorialname' => 'required|max:255',
				'tutorialvideo' => 'required|mimes:mp4,mov,ogg,qt'
			];
		}else{
			return [
				'tutorialname' => 'required|max:255|unique:tutorials,tutorialname,'.$this->route('id'),
				'tutorialvideo' => 'required|mimes:mp4,mov,ogg,qt'
			];
			
		}
	}
	public function messages(){
		return [
			'tutorialname.required' => 'Tutorial name is required.',
			'tutorialname.unique' => 'Tutorial name already exits.',
			'tutorialvideo.required' => 'Tutorial video is required.',
			'tutorialvideo.mimes' => 'Tutorial video must be a file of type: mp4, mov, ogg, qt.',
		];
    }
}
