<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Auth;
use App\Newsletter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NewsletterRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AdminDashboardController extends CommonController {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct(); 
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
		$return_data = array();
        $return_data['page_condition'] = 'dashboard_page';
        $return_data['site_title'] = trans('common.admin_dashboard_title') . ' | ' . $this->data['site_title'];
        return view('backend/admindashboard', array_merge($this->data, $return_data));
   }
   public function indexNewsletter() {
        $return_data = array();
        $newsletter_list = Newsletter::select('id','email','created_at')->where('deleted','=','0')->get();
        $return_data['newsletter_list'] = $newsletter_list;
        $return_data['site_title'] =  'Newsletter | ' . $this->data['site_title'];
        return view('backend/newsletter/index', array_merge($this->data, $return_data));
   }
   public function edit($id){
        $newsletter = Newsletter::find($id);
        if(count($newsletter) == 0){
            return redirect(route('list.newsletter'));
        }
        $return_data = array();
        $return_data['newsletter'] = $newsletter;
        $return_data['page_condition'] = 'newsletter_page';
        $return_data['site_title'] = trans('Manage Newsletter') . ' | ' . $this->data['site_title'];
        return view('backend/newsletter/create', array_merge($this->data, $return_data));
    }
    public function update(NewsletterRequest $request, $id){
        $sel_date = date('Y-m-d');
        $user_id = $this->login_user_id;
        $class = config('constants.ADMIN_PANEL');
        $this->log_insert("Newsletter updated" ,$user_id, $_REQUEST,$class);
        $newsletter = Newsletter::find($id);
        if(count($newsletter) == 0){
            return redirect(route('list.newsletter'));
        }
        $newsletter->email = $request->email;
        $newsletter->save();
        if ($request->save) {
            return redirect(route('edit.newsletter',$newsletter->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.newsletter'))->with('success_message', trans('Updated Successfully'));
        }
    }
   public function destroyNewsletter($id){
        Newsletter::where('id','=',$id)->delete();
        return redirect(route('list.newsletter'))->with('success_message', trans('Deleted Successfully'));
    }
}
