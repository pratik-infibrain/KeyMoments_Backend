<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\NotificationRequest;
use Illuminate\Http\RedirectResponse;
use File;
use App\mobileapp;
class notificationController extends CommonController
{
	function __construct() {
        parent::__construct();
		$this->middleware('auth');
		$permissionrole = (new \App\Helpers\Helper)->getrolepermision();
		if(!in_array('5',$permissionrole)):
			return redirect('/')->send();
		endif;
	}
	public function index()
	{

		$notification_list = Notification::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data = array();
		$return_data['page_condition'] = 'notification_page';
        $return_data['site_title'] = trans('notification') . ' | ' . $this->data['site_title'];
		$return_data['notification_list'] = $notification_list;
		return view('backend/notification/index', array_merge($this->data, $return_data));
	}
	public function create()
	{
		$return_data = array();
		$return_data['page_condition'] = 'notification_page';
		$return_data['notificationnamelist'] = Notification::where('deleted','=','0')->orderBy('id', 'DESC')->get();

		$return_data['site_title'] = trans('notification') . ' | ' . $this->data['site_title'];
		return view('backend/notification/create', array_merge($this->data, $return_data));
	}
	public function store(NotificationRequest $request)
	{
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("notification created" ,$user_id, $_REQUEST,$class);

		$notification = new Notification();
		$notification->notification_title = $request->notification_title;
		$notification->notification_content = $request->notification_content;
		$notification->status = 1;
		$notification->save();

		if($request->hasFile('notification_img'))
		{
			$image = $request->file('notification_img');

	        $imageName = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = $this->notification_upload_path;

	        $image->move($destinationPath, $imageName);
			$docuemntfileurl = url('/').'/'.$this->notification_upload_path.'/'.$imageName;

			$notification = Notification::find($notification->id);
			$notification->notification_img = $imageName;
			$notification->save();
		}

		// App Notification
		$data = [];
		$data['title'] = $request->notification_title;
		$data['msg'] = $request->notification_content;
		$data['icon'] = isset($docuemntfileurl) ? $docuemntfileurl : '';

		$arr_uid = mobileapp::select(['pairid'])->where ( 'deleted', 0 )->where ( 'pairid','!=', '' )->get();
		$user_pairids = [];
		foreach($arr_uid as $i) {
			$user_pairids[] = $i->pairid;
		}

		$this->_pushNotification($user_pairids, $data);
		// End App notification
		if ($request->save) {
            return redirect(route('edit.notification',$notification->id))->with('success_message', trans('Added Successfully'));
		}else{
            return redirect(route('list.notification'))->with('success_message', trans('Added Successfully'));
		}
	}

	public function _pushNotification($user_pairids = [], $data = [])
	{

		$msg = $data['msg'];
		$title = $data['title'];
		if (trim($msg) == '' || empty($user_pairids)) {
			return;
		}

		$title = 'Key Moments : ' . $title;
		$content = array("en" => $msg);
			//0bc46e63-89c7-4226-bf92-e1715f5492df

			//MjZlZTBmODctMjgzNy00MjExLTllZDgtMzJhZWJjMjY4ZDhj

		$fields = array(
				'app_id' => "0bc46e63-89c7-4226-bf92-e1715f5492df",
				'include_player_ids' => $user_pairids,
				'data' => array("foo" => "bar"),
				'headings'=> array("en" => $title),
				'contents' => $content,
				'large_icon' =>"ic_launcher_round.png",
		);
		$fields = json_encode($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic MjZlZTBmODctMjgzNy00MjExLTllZDgtMzJhZWJjMjY4ZDhj'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
	}

	public function edit($id){
		$notification = Notification::find($id);
		$return_data = array();
		$return_data['notification'] = $notification;
		$return_data['notificationnamelist'] = Notification::where('deleted','=','0')->orderBy('id', 'DESC')->get();
		$return_data['page_condition'] = 'notification_page';
		$return_data['site_title'] = trans('notification') . ' | ' . $this->data['site_title'];
		return view('backend/notification/create', array_merge($this->data, $return_data));
	}
	public function update(NotificationRequest $request, $id){
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("notification Updated" ,$user_id, $_REQUEST,$class);
		$notification = Notification::find($id);
		$notification->notification_title = $request->notification_title;
		$notification->notification_content = $request->notification_content;
		$notification->save();

		if($request->hasFile('notification_img'))
		{
			$image = $request->file('notification_img');

	        $imageName = time().'.'.$image->getClientOriginalExtension();
			$destinationPath = $this->notification_upload_path;

	        $image->move($destinationPath, $imageName);
			$docuemntfileurl = url('/').'/'.$this->notification_upload_path.'/'.$imageName;

			$notification = Notification::find($id);
			$notification->notification_img = $imageName;
			$notification->save();
		}

		if ($request->save) {
            return redirect(route('edit.notification',$notification->id))->with('success_message', trans('Updated Successfully'));
        }else{
            return redirect(route('list.notification'))->with('success_message', trans('Updated Successfully'));
        }
	}
	public function destroy($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Notification Deleted" ,$user_id, $request,$class);
		$notification = Notification::find($id);
		$notification->deleted = 1;
        $notification->save();
        return redirect(route('list.notification'))->with('success_message', trans('Deleted Successfully'));
	}
	public function setActivate($id){
		$request = array('id'=>$id);
		$user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("Notification Status Active" ,$user_id, $request,$class);
		$update_status = Notification::find($id);
		$update_status->status = 1;
		$update_status->save();
        return redirect(route('list.notification'))->with('success_message', trans('Activated Successfully'));
    }
    public function setInactivate($id) {
		$request = array('id'=>$id);
        $user_id = $this->login_user_id;
		$class = config('constants.ADMIN_PANEL');
		$this->log_insert("notification Status InActive" ,$user_id, $request,$class);
		$update_status = Notification::find($id);
		$update_status->status = 0;
		$update_status->save();
        return redirect(route('list.notification'))->with('success_message', trans('Inactivated Successfully'));
    }
}
