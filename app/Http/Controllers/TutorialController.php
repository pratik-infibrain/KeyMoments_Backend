<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Tutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\TutorialRequest;
use Illuminate\Http\RedirectResponse;
use File;

class TutorialController extends CommonController 
{
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('7',$permissionrole)):
			return redirect('/')->send();
		endif;
	}
	public function index()
	{
		$tutorial_list = Tutorial::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'tutorial_page';
        $return_data['site_title'] = trans('Tutorial') . ' | ' . $this->data['site_title'];
		$return_data['tutorial_list'] = $tutorial_list;
		return view('backend/tutorial/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'tutorial_page';
		$return_data['site_title'] = trans('Tutorial') . ' | ' . $this->data['site_title'];
		return view('backend/tutorial/create', array_merge($this->data, $return_data));
	}
	public function store(TutorialRequest $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Tutorial created" ,$user_id, $_REQUEST,$class);

		if($request->hasFile('tutorialvideo')) 
		{
			$image = $request->file('tutorialvideo');
			
	        $imageName = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = $this->document_upload_path;
			
	        $image->move($destinationPath, $imageName);
			$docuemntfileurl = url('/').'/'.$this->document_upload_path.'/'.$imageName;
		}

		$tutorial = new Tutorial();
		$tutorial->tutorialname = $request->tutorialname;
		$tutorial->tutorialvideo = $imageName;
		$tutorial->status = 1;
		$tutorial->save();
		if ($request->save) {
            return redirect(route('edit.tutorial',$tutorial->id))->with('success_message', trans('Tutorial Added Successfully'));
		}else{
            return redirect(route('list.tutorial'))->with('success_message', trans('Tutorial Added Successfully'));
		}
	}
	public function edit($id){
		$tutorial = Tutorial::find($id);
		$return_data = array();
		$return_data['tutorial'] = $tutorial;
		$return_data['page_condition'] = 'tutorial_page';
		$return_data['site_title'] = trans('Tutorial') . ' | ' . $this->data['site_title'];
		return view('backend/tutorial/create', array_merge($this->data, $return_data));
	}
	public function update(TutorialRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Tutorial Updated" ,$user_id, $_REQUEST,$class);
		
		$tutorial = Tutorial::find($id);
		$tutorial->tutorialname = $request->tutorialname;
		$tutorial->save();
		if($request->hasFile('tutorialvideo')) 
		{
			$image = $request->file('tutorialvideo');
			
	        $imageName = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = $this->document_upload_path;
			
	        $image->move($destinationPath, $imageName);
	        
	        $tutorial = Tutorial::find($id);
			$tutorial->tutorialvideo = $imageName;
			$tutorial->save();
	       
			$docuemntfileurl = url('/').'/'.$this->document_upload_path.'/'.$imageName;
		}
		if ($request->save) {
            return redirect(route('edit.tutorial',$tutorial->id))->with('success_message', trans('Tutorial Updated Successfully'));
        }else{
            return redirect(route('list.tutorial'))->with('success_message', trans('Tutorial Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Tutorial Deleted" ,$user_id, $request,$class);
		$tutorial = Tutorial::find($id);
		$tutorial->deleted = 1;
        $tutorial->save(); 
        return redirect(route('list.tutorial'))->with('success_message', trans('Tutorial Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Tutorial Status Active" ,$user_id, $request,$class);
		$update_status = Tutorial::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.tutorial'))->with('success_message', trans('Tutorial Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Tutorial Status InActive" ,$user_id, $request,$class);
		$update_status = Tutorial::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.tutorial'))->with('success_message', trans('Tutorial Inactivated Successfully'));
    }
}
