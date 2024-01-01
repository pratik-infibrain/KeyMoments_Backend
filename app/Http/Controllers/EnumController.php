<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Enum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\EnumRequest;
use Illuminate\Http\RedirectResponse;
use File;
class EnumController extends CommonController 
{
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('2',$permissionrole)):
			return redirect('/')->send();
		endif;	
	}
	public function index()
	{
		$enum_list = Enum::with('parentdetails')->where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'enum_page';
        $return_data['site_title'] = trans('Enum') . ' | ' . $this->data['site_title'];
		$return_data['enum_list'] = $enum_list;
		return view('backend/enum/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'enum_page';
		$return_data['enumnamelist'] = Enum::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		
		$return_data['site_title'] = trans('Enum') . ' | ' . $this->data['site_title'];
		return view('backend/enum/create', array_merge($this->data, $return_data));
	}
	public function store(EnumRequest $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Enum created" ,$user_id, $_REQUEST,$class);
		$enum = new Enum();
		$enum->enumname = $request->enumname;
		$enum->enumvalue = $request->enumvalue;
		$enum->parentname = $request->parentname;
		$enum->parentvalue = $request->parentvalue;
		$enum->status = 1;
		$enum->save();
		if ($request->save) {
            return redirect(route('edit.enum',$enum->id))->with('success_message', trans('Added Successfully'));
		}else{
            return redirect(route('list.enum'))->with('success_message', trans('Added Successfully'));
		}
	}
	public function edit($id){
		$enum = Enum::find($id);
		$return_data = array();
		$return_data['enum'] = $enum;
		$return_data['enumnamelist'] = Enum::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['page_condition'] = 'enum_page';
		$return_data['site_title'] = trans('Enum') . ' | ' . $this->data['site_title'];
		return view('backend/enum/create', array_merge($this->data, $return_data));
	}
	public function update(EnumRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Enum Updated" ,$user_id, $_REQUEST,$class);
		$enum = Enum::find($id);
		$enum->enumname = $request->enumname;
		$enum->enumvalue = $request->enumvalue;
		$enum->parentname = $request->parentname;
		$enum->parentvalue = $request->parentvalue;
		$enum->save();
		if ($request->save) {
            return redirect(route('edit.enum',$enum->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.enum'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Enum Deleted" ,$user_id, $request,$class);
		$enum = Enum::find($id);
		$enum->deleted = 1;
        $enum->save(); 
        return redirect(route('list.enum'))->with('success_message', trans('Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Enum Status Active" ,$user_id, $request,$class);
		$update_status = Enum::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.enum'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Enum Status InActive" ,$user_id, $request,$class);
		$update_status = Enum::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.enum'))->with('success_message', trans('Inactivated Successfully'));
    }
}
