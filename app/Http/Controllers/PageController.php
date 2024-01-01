<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Pages;
use App\User;
use File;
use Input;
use App\Website;
use App\PageAssignWebsite;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests\PageRequest;
use Illuminate\Http\RedirectResponse;
class PageController extends CommonController {
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
	}
	public function index(){
		$return_data = array();
		$page = Pages::where('deleted','=','0')->orderBy('position_order', 'ASC')->paginate(config('constants.ADMIN_DISPLAY_PER_PAGE'));
		$return_data['pagelist'] = $page;
        $return_data['page_condition'] = 'page_page';
		$return_data['site_title'] = trans('Pages') . ' | ' . $this->data['site_title'];
		return view('backend/page/index', array_merge($this->data, $return_data));
	}
	public function create(){
		$return_data = array();
		$return_data['page_condition'] = 'page_page';
		$return_data['site_title'] = trans('Pages') . ' | ' . $this->data['site_title'];
		return view('backend/page/create', array_merge($this->data, $return_data));
	}
	public function store(PageRequest $request){
		
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Pages created" ,$user_id, $_REQUEST,$class);
		$url_title = '';
		if($request->page_title != ''){
			$url_title = $this->slugify($request->page_title);
		}
        $page_max_position_order = Pages::where('deleted','=','0')->max('position_order');
        if($page_max_position_order == ""){
            $page_max_position_order = 1;
        }else{
            $page_max_position_order = $page_max_position_order + 1;
        }
		$page = new Pages();
		$page->page_title = $request->page_title;
		$page->menu_title = $request->menu_title;
		$page->url_title = $url_title;
        $page->position_order = $page_max_position_order;
		$page->description = $request->description;
		$page->meta_title = "";
		$page->meta_keyword = $request->meta_keyword;
		$page->meta_description = $request->meta_description;
		$page->status = 1;
		$page->save();
        if ($request->save) {
            return redirect(route('edit.page',$page->id))->with('success_message', trans('Pages Added Successfully'));
        }else{
            return redirect(route('list.page'))->with('success_message', trans('Pages Added Successfully'));
        }
	}
	public function edit($id){
		$return_data = array();
		$page = Pages::find($id);
		$return_data['page'] = $page;
		$return_data['page_condition'] = 'page_page';
		$return_data['page_type'] = 'pages_edit';
		$return_data['page_condition'] = 'pages';
		$return_data['site_title'] = trans('Pages') . ' | ' . $this->data['site_title'];
		return view('backend/page/create', array_merge($this->data, $return_data));
	}
	public function update(PageRequest $request, $id){
		
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Pages updated" ,$user_id, $_REQUEST,$class);
		$url_title = '';
		if($request->page_title != ''){
			$url_title = $this->slugify($request->page_title);
		}
		$page = Pages::find($id);
		$page->page_title = $request->page_title;
		$page->menu_title = $request->menu_title;
		$page->url_title = $url_title;
		$page->description = $request->description;
		$page->meta_title = "";
		$page->meta_keyword = $request->meta_keyword;
		$page->meta_description = $request->meta_description;
		$page->save();
        if ($request->save) {
            return redirect(route('edit.page',$page->id))->with('success_message', trans('Pages Updated Successfully'));
        }else{
            return redirect(route('list.page'))->with('success_message', trans('Pages Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Pages Deleted" ,$user_id, $request,$class);
		$pages = Pages::find($id);
		$pages->deleted = 1;
        $pages->save(); 
        return redirect(route('list.page'))->with('success_message', trans('Pages Deleted Successfully'));
	}
    public function setActivate($id){
        $request = array('id'=>$id); 
        $user_id = $this->login_user_id;
        $class = config('constants.ADMIN_PANEL');
        $this->log_insert("Pages Status Active" ,$user_id, $request,$class);
        $update_status = Pages::find($id);
        $update_status->status = 1;
        $update_status->save();
        return redirect(route('list.page'))->with('success_message', trans('Pages Activated Successfully'));
    }
    public function setInactivate($id) {
        $request = array('id'=>$id);
        $user_id = $this->login_user_id;
        $class = config('constants.ADMIN_PANEL');
        $this->log_insert("Pages Status InActive" ,$user_id, $request,$class);
        $update_status = Pages::find($id);
        $update_status->status = 0;
        $update_status->save();
        return redirect(route('list.page'))->with('success_message', trans('Pages Inactivated Successfully'));
    }
    
}

