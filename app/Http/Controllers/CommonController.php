<?php
namespace App\Http\Controllers;
use Auth;
use Route;
use App\Appslog;
use App\Module;
use App\Settings;
use App\EmailSmtp;
use App\Category;
use App\MessageSendHistories;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request as SRequest;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller { 
	protected $data = array();
	public $login_user_id = '';
    public function __construct(){
		date_default_timezone_set('UTC');
		//echo '--'.$current_route_name = Route::currentRouteName();
		$this->data['site_title'] = config('constants.SITE_NAME');
		$this->data['site_name'] = config('constants.SITE_NAME');
		$setting_detail = $this->get_setting_detail();
		if($setting_detail['site_title'] != ''){
			$this->data['site_title'] = $setting_detail['site_title'];
			$this->data['site_name'] = $setting_detail['site_title'];
		}
        $id = Auth::id();
        $user = array();
        if ($id != '') {
            $user = Auth::user();
			if ($user->status == 0){
				Auth::logout();
                return Redirect::to('login')->with('error_message', trans('message.inactive_loginstatus'));
            }
            $this->login_user_id = $id;
            $this->data['login_user_id'] = $this->login_user_id;
            $current_route_name = Route::currentRouteName();
        }
		$this->current_volume_path = config('constants.DEFAULT_VOLUME_PATH');
        $this->current_volume_path_upload = config('constants.DEFAULT_VOLUME_PATH_UPLOAD');
		$this->admin_user_attachment = $this->current_volume_path.config('constants.ADMIN_USER_IMAGE');
        $this->course_upload_path = $this->current_volume_path_upload.config('constants.COURSE_IMAGE');
        $this->topic_upload_path = $this->current_volume_path_upload.config('constants.TOPIC_IMAGE');
        $this->document_upload_path = $this->current_volume_path_upload.config('constants.DOCUMENT_IMAGE');
        $this->notification_upload_path = $this->current_volume_path_upload.config('constants.notification_IMAGE');
        $this->topic_image_upload_path = $this->current_volume_path_upload.config('constants.TOPIC_IMAGE_IMAGE');
        $this->topic_video_upload_path = $this->current_volume_path_upload.config('constants.TOPIC_VIDEO_IMAGE');
		//$this->admin_user_attachment_thumb = $this->current_volume_path.config('constants.ADMIN_USER_IMAGE_THUMB');
        $this->banner_attachment = $this->current_volume_path.config('constants.BANNER_IMAGE');
        $this->extra_attachment = $this->current_volume_path.config('constants.EXTRA_IMAGE');
        $this->product_attachment = $this->current_volume_path.config('constants.PRODUCT_IMAGE');
        $this->supplier_attachment = $this->current_volume_path.config('constants.SUPPLIER_IMAGE');

		$url = URL::to('/');
        $this->data['site_full_path']=$url; 
        $this->site_full_path = $url;
		$this->data['admin_user_profile_image']=$this->admin_user_attachment; 
		$this->data['page_condition'] = 'default';
    }
	protected function get_setting_detail() {
		$all_setting_detail = array();
		$AdminSetting_list = Settings::select('id','setting_key','setting_value')->get();
		$site_title = '';
		if(count($AdminSetting_list) != 0){
			foreach($AdminSetting_list as $AdminSetting_list_val){
				if($AdminSetting_list_val->id == '1'){
					$site_title  = $AdminSetting_list_val->setting_value;
				}
			}
		}
		$all_setting_detail['site_title'] = $site_title;
		return $all_setting_detail; 
    }
	public function log_insert($action, $user_id , $description,$class = 0){
        $data            = array();
        $data['user_id'] = $user_id;
        $data['action']      = $action;
        $data['description'] = $description;
        if (is_array($data['description']) || is_object($data['description'])) {
            $data['description'] = json_encode($data['description'], JSON_UNESCAPED_UNICODE);
        }
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];//$this->getClientIp();//Request::getClientIp();
		$data['class'] = $class;
        $data['created_at'] = new \DateTime();
        $create = Appslog::create($data);
        return $create->id;
    }
	public function slugify($text){
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		// trim
		$text = trim($text, '-');
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
		// lowercase
		$text = strtolower($text);
		if (empty($text)) {
			return 'n-a';
		}
		return $text;
	}
	public function _pre($all_array){
		echo '<pre>';
		print_r($all_array);
		echo '</pre>';
	}
    public function send_email($subject,$Email_body_content,$to_email_address,$username,$attachment_1 = "",$attachment_2 = ""){
        $html_data = $Email_body_content;
        $from_email = ""; 
        $email_smtp_id = config('constants.Email_Smtp_Id');
        $email_smtp_detail = EmailSmtp::where('id','=',$email_smtp_id)->where('deleted','=','0')->first();
        if(count($email_smtp_detail) > 0){

            $logo_path =  URL::to('/').'/images/logo.png';
            $port_number = $email_smtp_detail->smtp_port;
            $encryption_type = $email_smtp_detail->encryption_type;
            $smtp_host = $email_smtp_detail->smpt_host;
            $smtp_password = $email_smtp_detail->smtp_password;
            $smtp_user_name = $email_smtp_detail->smtp_username;
            $from_email = $email_smtp_detail->from_email;
            $from_name = $email_smtp_detail->from_name;

            config( ['mail' => ['from' => ['address' => $from_email, 'name' => $from_name], 'host'=>$smtp_host, 'port'=>$port_number, 'username'=>$smtp_user_name,  'password'=>$smtp_password, 'encryption'=>$encryption_type, 'driver'=>'smtp']]);

            $view = View::make('emails.emailtemplate', [
                'site_title' => $this->data['site_title'],
                'site_url' => $this->data['site_full_path'],
                'site_email' => "",
                'site_phone' => "",
                'logo_path' => $logo_path,
                'Email_body_content' => $Email_body_content
            ]);
            $html_data = $view->render();
            try {    
                Mail::send(array(), array(), function($message)use ($to_email_address,$username,$subject,$html_data,$attachment_1,$attachment_2) {
                    $message->to($to_email_address,$username)->subject($subject);
                    if($attachment_1 != ''){
                        $message->attach($attachment_1);
                    }
                    if($attachment_2 != ''){
                        $message->attach($attachment_2);
                    }
                    $message->setBody($html_data, 'text/html');
                });
                //Mail::to('developer@gmail.com')->send(new ExceptionOccured($html));
                /*$message = \Swift_Message::newInstance($subject)->setFrom([$from_email => $from_name])->setTo([$to_email_address => $username])->setBody($html_data, 'text/html');
                if($attachment_1 != ''){
                    $message->attach( \Swift_Attachment::fromPath($attachment_1));
                }
                if($attachment_2 != ''){
                    $message->attach( \Swift_Attachment::fromPath($attachment_2));
                }
                */
                $message_responce = 1;
            }catch (\Swift_TransportException $e) {
               $message_responce = $e->getMessage();
            }    
        }else{
            $message_responce = "No Found SMTP";
        }   
        $message_send_insert = new MessageSendHistories();
        $message_send_insert->type = $subject;
        $message_send_insert->from_email = $from_email; 
        $message_send_insert->to_email = $to_email_address;
        $message_send_insert->message_data = $html_data;
        $message_send_insert->message_responce = $message_responce;
        $message_send_insert->save();
        return $message_responce;
    }
    public function getmodule()
    {
        $modulelist = Module::where('deleted','0')->get();
        return $modulelist;
    }
}
