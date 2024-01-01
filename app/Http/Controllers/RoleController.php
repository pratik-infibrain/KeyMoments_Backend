<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Role;
use App\Roleprivilege;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\RedirectResponse;
use File;
class RoleController extends CommonController 
{
	function __construct() 
	{
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('1',$permissionrole)):
			return redirect('/')->send();
		endif;		
		
	}
	public function index(){
		$role_list = Role::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
        $return_data['site_title'] = trans('Role') . ' | ' . $this->data['site_title'];
        $return_data['page_condition'] = 'role_page';
		$return_data['role_list'] = $role_list;
		return view('backend/role/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'role_page';
		$return_data['site_title'] = trans('Role') . ' | ' . $this->data['site_title'];
		return view('backend/role/create', array_merge($this->data, $return_data));
	}
	public function store(RoleRequest $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Role created" ,$user_id, $_REQUEST,$class);
		$role = new Role();
		$role->name = $request->name;
		$role->description = $request->description;
		$role->status = 1;
		$role->save();
		$rolep = new Roleprivilege();
		$rolep->roleid = $role->id;
		$rolep->save();
		if ($request->save) {
            return redirect(route('edit.role',$role->id))->with('success_message', trans('Added Successfully'));
		}else{
            return redirect(route('list.role'))->with('success_message', trans('Added Successfully'));
		}
	}
	public function edit($id){
		$role = Role::find($id);
		$return_data = array();
		$return_data['role'] = $role;
		$return_data['page_condition'] = 'role_page';
		$return_data['site_title'] = trans('Role') . ' | ' . $this->data['site_title'];
		return view('backend/role/create', array_merge($this->data, $return_data));
	}
	public function update(RoleRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Role Updated" ,$user_id, $_REQUEST,$class);
		$role = Role::find($id);
		$role->name = $request->name;
		$role->description = $request->description;
		$role->save();
		if ($request->save) {
            return redirect(route('edit.role',$role->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.role'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$usercount = User::where('role_id',$id)->where('deleted','0')->count();
		if($usercount > 0):
			return redirect(route('list.role'))->with('error_message', trans('Role not delete because user assign in this role!'));
		else:
			$user_id = $this->login_user_id;
			$class = config('constants.ADMIN_PANEL');
			$this->log_insert("Role Deleted" ,$user_id, $request,$class);

			$role = Role::find($id);
			$role->deleted = 1;
	        $role->save(); 
	        return redirect(route('list.role'))->with('success_message', trans('Deleted Successfully'));
		endif;
		
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Role Status Active" ,$user_id, $request,$class);
		$update_status = Role::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.role'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Role Status InActive" ,$user_id, $request,$class);
		$update_status = Role::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.role'))->with('success_message', trans('Inactivated Successfully'));
    }
}
