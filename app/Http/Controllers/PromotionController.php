<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\PromotionRequest;
use Illuminate\Http\RedirectResponse;
use File;
class PromotionController extends CommonController 
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
		$promotion_list = Promotion::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'promotion_page';
        $return_data['site_title'] = trans('promotion') . ' | ' . $this->data['site_title'];
		$return_data['promotion_list'] = $promotion_list;
		return view('backend/promotion/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'promotion_page';
		$return_data['promotionnamelist'] = Promotion::where('deleted','=','0')->orderBy('id', 'DESC')->get();

		$return_data['site_title'] = trans('promotion') . ' | ' . $this->data['site_title'];
		return view('backend/promotion/create', array_merge($this->data, $return_data));
	}
	public function store(PromotionRequest $request)
	{
		
		$validformdate=explode("/",$request->valid_form_date);
		$validformdate=$validformdate[2]."-".$validformdate[1]."-".$validformdate[0];
	
		$valid_to_date=explode("/",$request->valid_to_date);
		$valid_to_date=$valid_to_date[2]."-".$valid_to_date[1]."-".$valid_to_date[0];	
		
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("promotion created" ,$user_id, $_REQUEST,$class);
		$promotion = new Promotion();
		$promotion->promotion_code = $request->promotion_code;
		$promotion->promotion_content = $request->promotion_content;
		$promotion->valid_form_date = $validformdate;
		$promotion->valid_to_date = $valid_to_date;
		$promotion->type = $request->type;
		$promotion->value = $request->value_percentage;
		//$promotion->value_amount = $request->value_amount;
		//$promotion->value_free_trial = $request->value_free_trial;
		$promotion->status = 1;
		$promotion->save();
		if ($request->save) {
            return redirect(route('edit.promotion',$promotion->id))->with('success_message', trans('Added Successfully'));
		}else{
            return redirect(route('list.promotion'))->with('success_message', trans('Added Successfully'));
		}
	}
	public function edit($id){
		$promotion = Promotion::find($id);
		$return_data = array();
		$return_data['promotion'] = $promotion;
		$return_data['promotionnamelist'] = Promotion::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['page_condition'] = 'promotion_page';
		$return_data['site_title'] = trans('promotion') . ' | ' . $this->data['site_title'];
		return view('backend/promotion/create', array_merge($this->data, $return_data));
	}
	public function update(PromotionRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');

		$this->log_insert("promotion Updated" ,$user_id, $_REQUEST,$class);
		$validformdate=explode("/",$request->valid_form_date);
		$validformdate=$validformdate[2]."-".$validformdate[1]."-".$validformdate[0];
	
		$valid_to_date=explode("/",$request->valid_to_date);
		$valid_to_date=$valid_to_date[2]."-".$valid_to_date[1]."-".$valid_to_date[0];	

		$promotion = Promotion::find($id);
		$promotion->promotion_code = $request->promotion_code;
		$promotion->promotion_content = $request->promotion_content;
		$promotion->valid_form_date = $validformdate;
		$promotion->valid_to_date = $valid_to_date;
		$promotion->type = $request->type;
		$promotion->value = $request->value_percentage;
		
		$promotion->save();
		if ($request->save) {
            return redirect(route('edit.promotion',$promotion->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.promotion'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Promotion Deleted" ,$user_id, $request,$class);
		$promotion = Promotion::find($id);
		$promotion->deleted = 1;
        $promotion->save(); 
        return redirect(route('list.promotion'))->with('success_message', trans('Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id); 
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Promotion Status Active" ,$user_id, $request,$class);
		$update_status = Promotion::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.promotion'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("promotion Status InActive" ,$user_id, $request,$class);
		$update_status = Promotion::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.promotion'))->with('success_message', trans('Inactivated Successfully'));
    }
}
