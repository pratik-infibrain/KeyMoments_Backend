<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AdminUserRequest;
use Illuminate\Http\RedirectResponse;
use File;
class AdminUserController extends CommonController 
{
	function __construct() 
	{
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('8',$permissionrole)):
			return redirect('/')->send();
		endif;
	}
	public function index()
	{
		$admin_user_list = User::where('deleted','=','0')->orderBy('id', 'DESC')->paginate(config('constants.ADMIN_DISPLAY_PER_PAGE'));
		$return_data = array();
        $return_data['site_title'] = trans('Admin User') . ' | ' . $this->data['site_title'];
		$return_data['admin_user_list'] = $admin_user_list;
		$return_data['page_condition'] = 'admin_user_page';
		return view('backend/admin_user/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$role_list = Role::where('deleted','=','0')->where('status','=','1')->orderBy('id', 'DESC')->get();
		$return_data['role_list'] = $role_list;
		$return_data['page_condition'] = 'admin_user_page';
		$return_data['site_title'] = trans('Admin User') . ' | ' . $this->data['site_title'];
		return view('backend/admin_user/create', array_merge($this->data, $return_data));
	}
	public function store(AdminUserRequest $request){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Admin User created" ,$user_id, $_REQUEST,$class);
		$user = new User();
		//$user->name = $request->name;
		$user->email = $request->email;
		$user->firstname = $request->firstname;
		$user->lastname = $request->lastname;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->role_id = $request->role_id;
		$user->password = bcrypt($request->password);
		$user->status = 1;
		$user->save();
		if ($request->save) {
            return redirect(route('edit.admin_user',$user->id))->with('success_message', trans('Admin User Added Successfully'));
		}else{
            return redirect(route('list.admin_user'))->with('success_message', trans('Admin User Added Successfully'));
		}
	}
	public function view($id)
	{
		$admin_user = User::with('roledetails')->where('id',$id)->first();
		$return_data = array();
		$role_list = Role::where('deleted','=','0')->where('status','=','1')->orderBy('id', 'DESC')->get();
		$return_data['role_list'] = $role_list;
		$return_data['admin_user'] = $admin_user;
		$return_data['page_condition'] = 'admin_user_page';
		$return_data['admin_user_attachment'] = $this->admin_user_attachment;
		$return_data['site_title'] = trans('Admin User') . ' | ' . $this->data['site_title'];
		return view('backend/admin_user/view', array_merge($this->data, $return_data));
	}
	public function edit($id){
		$admin_user = User::find($id);
		$return_data = array();
		$role_list = Role::where('deleted','=','0')->where('status','=','1')->orderBy('id', 'DESC')->get();
		$return_data['role_list'] = $role_list;
		$return_data['admin_user'] = $admin_user;
		
		$return_data['page_condition'] = 'admin_user_page';
		$return_data['admin_user_attachment'] = $this->admin_user_attachment;
		$return_data['site_title'] = trans('Admin User') . ' | ' . $this->data['site_title'];
		return view('backend/admin_user/create', array_merge($this->data, $return_data));
	}
	public function update(AdminUserRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Admin User Updated" ,$user_id, $_REQUEST,$class);
		$user = User::find($id);
		//$user->name = $request->name;
		$user->role_id = $request->role_id;
		$user->firstname = $request->firstname;
		$user->lastname = $request->lastname;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->save();
        if($request->save) 
        {
            return redirect(route('edit.admin_user',$user->id))->with('success_message', trans('Admin User Updated Successfully'));
        }
        else
        {
            return redirect(route('list.admin_user'))->with('success_message', trans('Admin User Updated Successfully'));
        }
	}
	public function destroy($id)
	{
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Admin User Deleted" ,$user_id, $request,$class);
		$admin_user = User::find($id);
		$admin_user->deleted = 1;
        $admin_user->save(); 
        return redirect(route('list.admin_user'))->with('success_message', trans('Admin User Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Admin User Status Active" ,$user_id, $request,$class);
		$update_status = User::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.admin_user'))->with('success_message', trans('Admin User Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Admin User Status InActive" ,$user_id, $request,$class);
		$update_status = User::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.admin_user'))->with('success_message', trans('Admin User Inactivated Successfully'));
    }
}
