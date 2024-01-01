<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Role;
use App\Module;
use App\Roleprivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use App\Http\Requests\PackageRequest;
use Illuminate\Http\RoleprivilegeResponse;
use File;
class RoleprivilegeController extends CommonController 
{
	function __construct() 
	{
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('9',$permissionrole)):
			return redirect('/')->send();
		endif;
	}
	public function index()
	{
		$roleprivilege_list = Roleprivilege::with('roledetails')->where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		
		if($roleprivilege_list):
			foreach($roleprivilege_list as $roelslist):
				$modu = explode(',',$roelslist->moduleid);
				$getmodule = Module::select('name')->where('deleted','=','0')->whereIn('id', $modu)->get();
				if($getmodule):
					$moduname = array();
					foreach($getmodule as $modlu):
						array_push($moduname,$modlu->name);
					endforeach;	
					$roelslist->modulesdetails = implode(',',$moduname);
				else:
					$roelslist->modulesdetails = '-';
				endif;	
				
			endforeach;	
		endif;	

		$return_data['page_condition'] = 'roleprivileges_page';
        $return_data['site_title'] = trans('Role Privilege') . ' | ' . $this->data['site_title'];
		$return_data['roleprivilege_list'] = $roleprivilege_list;
		return view('backend/roleprivilege/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'roleprivileges_page';
		$return_data['rolelist'] = Role::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['modulelist'] = Module::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['site_title'] = trans('Role') .' | '.trans('Privilege'). ' | ' . $this->data['site_title'];
		return view('backend/roleprivilege/create', array_merge($this->data, $return_data));
	}
	public function store(Request $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Role privilege created" ,$user_id, $_REQUEST,$class);

		$role = $request->role;
		$modules = $request->modules;
		$roles = Roleprivilege::where('roleid',$role)->where('deleted','0')->first();
		if($roles):
			$roleprivilege = Roleprivilege::find($roles->id);
		else:
			$roleprivilege = new Roleprivilege();	
		endif;	
		$roleprivilege->roleid = $role;
		$roleprivilege->moduleid = implode(',',$modules);
		$roleprivilege->save();
		if($request->save) 
		{
            return redirect(route('add.roleprivileges'))->with('success_message', trans('Added Successfully'));
		}
		else
		{
            return redirect(route('list.roleprivileges'))->with('success_message', trans('Added Successfully'));
		}
	}
    public function getPermission($id)
    {
    	$roleprivilege = Roleprivilege::where('deleted','=','0')->where('roleid',$id)->orderBy('id', 'DESC')->first();
    	$moduleids = array();
    	if($roleprivilege){
    		$moduleids = explode(',',$roleprivilege->moduleid);
    	}
		$modulelist = Module::where('deleted','=','0')->orderBy('id', 'ASC')->get();
    	$output ='<div class="col-md-8 col-sm-8 col-xs-12">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>Modules</th>
					</tr>
				</thead>
				<tbody>';
					if($modulelist):
						foreach($modulelist as $moduless):
							$sele = '';
							if(in_array($moduless->id,$moduleids)):
								$sele = ' checked';
							endif;	
						$output .='<tr>
							<td><input type="checkbox" class="allmodules" name="modules[]" id="modules'.$moduless->id.'" value="'.$moduless->id.'" '.$sele.'>  <label for="modules'.$moduless->id.'">'.$moduless->name.'</label></td></tr>';
						endforeach;
					endif;
				$output .='</tbody>
			</table><span class="error" id="moduleerror"></span>
		</div>';	

		echo $output;
    }
}
