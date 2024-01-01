<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Keymoment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use File;
class KeymomentController extends CommonController 
{
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('6',$permissionrole)):
			return redirect('/')->send();
		endif;
	}
	public function index()
	{
		$keymoment_list = Keymoment::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'keymoment_page';
        $return_data['site_title'] = trans('keymoment') . ' | ' . $this->data['site_title'];
		$return_data['keymoment_list'] = $keymoment_list;
		return view('backend/keymoment/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'keymoment_page';
		$return_data['keymomentnamelist'] = Keymoment::where('deleted','=','0')->orderBy('id', 'DESC')->get();

		$return_data['site_title'] = trans('keymoment') . ' | ' . $this->data['site_title'];
		return view('backend/keymoment/create', array_merge($this->data, $return_data));
	}
	public function store(Request $request)
	{
		
		$validformdate=explode("/",$request->valid_form_date);
		$validformdate=$validformdate[2]."-".$validformdate[1]."-".$validformdate[0];
	
		$valid_to_date=explode("/",$request->valid_to_date);
		$valid_to_date=$valid_to_date[2]."-".$valid_to_date[1]."-".$valid_to_date[0];	
		
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("keymoment created" ,$user_id, $_REQUEST,$class);
		$keymoment = new Keymoment();
		$keymoment->title = $request->title;
		$keymoment->status = 1;
		$keymoment->save();
		if ($request->save) {
            return redirect(route('edit.keymoment',$keymoment->id))->with('success_message', trans('Added Successfully'));
		}else{
            return redirect(route('list.keymoment'))->with('success_message', trans('Added Successfully'));
		}
	}
	public function edit($id){
		$keymoment = Keymoment::find($id);
		$return_data = array();
		$return_data['keymoment'] = $keymoment;
		$return_data['keymomentnamelist'] = Keymoment::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['page_condition'] = 'keymoment_page';
		$return_data['site_title'] = trans('keymoment') . ' | ' . $this->data['site_title'];
		return view('backend/keymoment/create', array_merge($this->data, $return_data));
	}
	public function update(Request $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');

		$this->log_insert("keymoment Updated" ,$user_id, $_REQUEST,$class);
	

		$keymoment = Keymoment::find($id);
		$keymoment->title = $request->title;
		
		$keymoment->save();
		if ($request->save) {
            return redirect(route('edit.keymoment',$keymoment->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.keymoment'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Keymoment Deleted" ,$user_id, $request,$class);
		$keymoment = Keymoment::find($id);
		$keymoment->deleted = 1;
        $keymoment->save(); 
        return redirect(route('list.keymoment'))->with('success_message', trans('Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Keymoment Status Active" ,$user_id, $request,$class);
		$update_status = Keymoment::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.keymoment'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("keymoment Status InActive" ,$user_id, $request,$class);
		$update_status = Keymoment::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.keymoment'))->with('success_message', trans('Inactivated Successfully'));
    }
}
