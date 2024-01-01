<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Package;
use App\mobileapp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PackageRequest;
use Illuminate\Http\RedirectResponse;
use File;
class PackageController extends CommonController 
{
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('3',$permissionrole)):
			return redirect('/')->send();
		endif;	
	}
	public function index()
	{
		$Package_list = Package::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'Package_page';
        $return_data['site_title'] = trans('Package') . ' | ' . $this->data['site_title'];
		$return_data['Package_list'] = $Package_list;
		return view('backend/Package/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'Package_page';
		$return_data['Packagenamelist'] = Package::where('deleted','=','0')->orderBy('id', 'DESC')->get();

		$return_data['site_title'] = trans('Package') . ' | ' . $this->data['site_title'];
		return view('backend/Package/create', array_merge($this->data, $return_data));
	}
	public function store(PackageRequest $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Package created" ,$user_id, $_REQUEST,$class);
		$package = new Package();
		$package->package_name = $request->package_name;
		$package->package_price = $request->package_price;
		$package->number_of_notes = $request->number_of_notes;
		$package->number_of_photos = $request->number_of_photos;
		$package->number_of_videos = $request->number_of_videos;
		$package->data_limit = $request->data_limit;
		$package->status = 1;
		$package->save();
		if ($request->save) {
            return redirect(route('edit.Package',$package->id))->with('success_message', trans('Added Successfully'));
		}else{
            return redirect(route('list.Package'))->with('success_message', trans('Added Successfully'));
		}
	}
	public function edit($id){
		$package = Package::find($id);
		$return_data = array();
		$return_data['package'] = $package;
		$return_data['Packagenamelist'] = Package::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['page_condition'] = 'Package_page';
		$return_data['site_title'] = trans('Package') . ' | ' . $this->data['site_title'];
		return view('backend/Package/create', array_merge($this->data, $return_data));
	}
	public function update(PackageRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Package Updated" ,$user_id, $_REQUEST,$class);
		$package = Package::find($id);
		$package->package_name = $request->package_name;
		$package->package_price = $request->package_price;
		$package->number_of_notes = $request->number_of_notes;
		$package->number_of_photos = $request->number_of_photos;
		$package->number_of_videos = $request->number_of_videos;
		$package->data_limit = $request->data_limit;
		$package->save();
		if ($request->save) {
            return redirect(route('edit.Package',$package->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.Package'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Package Deleted" ,$user_id, $request,$class);
		$countmobiel = mobileapp::where('package',$id)->count();
		if($countmobiel>0):
			return redirect(route('list.Package'))->with('error_message', trans('Package Not Deleted Because Assign To Package'));
		else:
			$package = Package::find($id);
			$package->deleted = 1;
			$package->save(); 
			return redirect(route('list.Package'))->with('success_message', trans('Deleted Successfully'));
		endif;	
		
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Package Status Active" ,$user_id, $request,$class);
		$update_status = Package::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.Package'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Package Status InActive" ,$user_id, $request,$class);
		$update_status = Package::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.Package'))->with('success_message', trans('Inactivated Successfully'));
    }
}
