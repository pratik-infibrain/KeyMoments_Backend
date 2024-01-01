<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\mobileapp;
use App\userexicuter;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\mobileappRequest;
use Illuminate\Http\RedirectResponse;
use File;
class mobileappController extends CommonController 
{
	function __construct() 
	{
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('4',$permissionrole)):
			return redirect('/')->send();
		endif;	
	}
	public function index()
	{
		$mobileapp_list = mobileapp::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'mobileapp_page';
        $return_data['site_title'] = trans('mobileapp') . ' | ' . $this->data['site_title'];
		$return_data['mobileapp_list'] = $mobileapp_list;
		return view('backend/mobileapp/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'mobileapp_page';
		$return_data['Packagenamelist'] = Package::where('deleted','=','0')->where('status','=','1')->orderBy('id', 'DESC')->get();
		//print_r ($Package_list);
		$return_data['site_title'] = trans('Mobile App') . ' | ' . $this->data['site_title'];
		$return_data['userexicuterarray'] = array('','','');
		return view('backend/mobileapp/create', array_merge($this->data, $return_data));
	}
	public function store(mobileappRequest $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("mobileapp created" ,$user_id, $_REQUEST,$class);
		$mobileapp = new mobileapp();
		$mobileapp->email = $request->email;
		$mobileapp->password = $request->password;
		$mobileapp->full_name = $request->full_name;
		$mobileapp->mobile_number = $request->mobile_number;
		$mobileapp->gender = $request->gender;
		$mobileapp->age = $request->age;
		$mobileapp->marital_status = $request->marital_status;
		$mobileapp->children = $request->children;
		$mobileapp->education = $request->education;
		$mobileapp->military_status = $request->military_status;
		$mobileapp->employment = $request->employment;
		//$mobileapp->list_of_executors = $request->list_of_executors;
		$mobileapp->package = $request->package;
		$mobileapp->status = 1;
		$mobileapp->save();
		if($request->save) 
		{
			$ids=$mobileapp->id;
			for($i=0;$i<count($request->list_of_executors);$i++)
			{
				$userexicuter = new userexicuter();
				$userexicuter->name = $request->list_of_executors[$i];
				$userexicuter->user_id = $ids;
				$userexicuter->save();
			}	
            return redirect(route('edit.mobileapp',$mobileapp->id))->with('success_message', trans('Added Successfully'));
		}
		else
		{
            return redirect(route('list.mobileapp'))->with('success_message', trans('Added Successfully'));
		}
	} 
	public function edit($id)
	{
		$mobileapp = mobileapp::find($id);
		$return_data = array();
		$return_data['mobileapp'] = $mobileapp;
		$return_data['mobileapplist'] = mobileapp::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['Packagenamelist'] = Package::where('deleted','=','0')->where('status','=','1')->orderBy('id', 'DESC')->get();
		$return_data['page_condition'] = 'mobileapp_page';
		$return_data['site_title'] = trans('mobileapp') . ' | ' . $this->data['site_title'];
		
		$return_data['userexicuterarray'] = userexicuter::where('user_id','=',$id)->get();
		if(count($return_data['userexicuterarray'])>0)
		{
			if(count($return_data['userexicuterarray'])=='1')
			{
			
				$merge['0'] = $return_data['userexicuterarray'];
				$merge['1'] = array('');
				$merge['2'] = array('');
				
				$return_data['userexicuterarray'] = $merge;
			}
			if(count($return_data['userexicuterarray'])=='2')
			{
				$merge = $return_data['userexicuterarray'];
				$merge['2'] = array('');
				$return_data['userexicuterarray'] = $merge;
			}
			if(count($return_data['userexicuterarray'])=='3')
			{
				$merge = $return_data['userexicuterarray'];
				$return_data['userexicuterarray'] = $merge;
			}
		}	
		else
		{
			$return_data['userexicuterarray'] = array('','','');
		}	
		//print_r($return_data);
		return view('backend/mobileapp/create', array_merge($this->data, $return_data));
	}
	public function update(mobileappRequest $request, $id)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("mobileapp Updated" ,$user_id, $_REQUEST,$class);
		$mobileapp = mobileapp::find($id);
		//$mobileapp->email = $request->email;
		//$mobileapp->password = $request->password;
		$mobileapp->full_name = $request->full_name;
		$mobileapp->mobile_number = $request->mobile_number;
		$mobileapp->gender = $request->gender;
		$mobileapp->age = $request->age;
		$mobileapp->marital_status = $request->marital_status;
		$mobileapp->children = $request->children;
		$mobileapp->education = $request->education;
		$mobileapp->military_status = $request->military_status;
		$mobileapp->employment = $request->employment;
	//	$mobileapp->list_of_executors = $request->list_of_executors;
		$mobileapp->package = $request->package;
		$mobileapp->save();
		//print_r($request->list_of_executors);
		if(is_array($request->list_of_executors)){
			foreach($request->list_of_executors as $key => $val){
				//echo $key."====>".$val."VVV";
                $blog_category_assign_count = userexicuter::where('user_id',"=",$id)->where('id', '=', $key)->count();
                if($blog_category_assign_count == 0){
                    $insert_blog_category = new userexicuter();
                    $insert_blog_category->user_id=$id;
                    $insert_blog_category->name=$val;
                    $insert_blog_category->save();
                }else{
                    $category_assign = userexicuter::where('user_id',"=",$id)->where('id', '=', $key)->update(array('name' => $val));
                }
			}
		}
		//exit;
		
		if ($request->save) 
		{
			return redirect(route('edit.mobileapp',$mobileapp->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.mobileapp'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function changePassword(mobileappRequest $request, $id)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("mobileapp Updated" ,$user_id, $_REQUEST,$class);
		$mobileapp = mobileapp::find($id);

		$mobileapp->password = $request->password;

		$mobileapp->save();

		if ($request->save)
		{
			return redirect(route('edit.mobileapp',$mobileapp->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.mobileapp'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("mobileapp Deleted" ,$user_id, $request,$class);
		$mobileapp = mobileapp::find($id);
		$mobileapp->deleted = 1;
        $mobileapp->save(); 
        return redirect(route('list.mobileapp'))->with('success_message', trans('Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("mobileapp Status Active" ,$user_id, $request,$class);
		$update_status = mobileapp::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.mobileapp'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("mobileapp Status InActive" ,$user_id, $request,$class);
		$update_status = mobileapp::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.mobileapp'))->with('success_message', trans('Inactivated Successfully'));
    }
}
