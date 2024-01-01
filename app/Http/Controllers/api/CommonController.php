<?php

namespace App\Http\Controllers\api;

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
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request as SRequest;
use Illuminate\Support\Facades\Session;
use App\mobileapp;
use App\NotificationAlert;
use App\User_setting;
use App\FavouriteMessages;
use App\InspirationalMessages;
use App\UnreadUserMessage;

class CommonController extends Controller {
	protected $data = array ();
	protected $arr__user_fields = ['id', 'email', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender', 'dateofbirth', 'marital_status', 'education', 'military_status', 'employment', 'short_detail', 'profile_photo'];
	protected $arr__package_fields = ['id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit'];

	public $login_user_id = '';
	public function __construct() {
		date_default_timezone_set ( 'UTC' );
		// echo '--'.$current_route_name = Route::currentRouteName();
		$this->data ['site_title'] = config ( 'constants.SITE_NAME' );
		$this->data ['site_name'] = config ( 'constants.SITE_NAME' );
		$id = Auth::id ();
		$user = array ();
		if ($id != '') {
			$user = Auth::user ();
			if ($user->status == 0) {
				Auth::logout ();
				return Redirect::to ( 'login' )->with ( 'error_message', trans ( 'message.inactive_loginstatus' ) );
			}
			$this->login_user_id = $id;
			$this->data ['login_user_id'] = $this->login_user_id;
			$current_route_name = Route::currentRouteName ();
		}
		$this->current_volume_path = config ( 'constants.DEFAULT_VOLUME_PATH' );
		$this->current_volume_path_upload = config ( 'constants.DEFAULT_VOLUME_PATH_UPLOAD' );
		$this->admin_user_attachment = $this->current_volume_path . config ( 'constants.ADMIN_USER_IMAGE' );
		$this->course_upload_path = $this->current_volume_path_upload . config ( 'constants.COURSE_IMAGE' );
		$this->topic_upload_path = $this->current_volume_path_upload . config ( 'constants.TOPIC_IMAGE' );
		$this->document_upload_path = $this->current_volume_path_upload . config ( 'constants.DOCUMENT_IMAGE' );
		$this->notification_upload_path = $this->current_volume_path_upload . config ( 'constants.notification_IMAGE' );
		$this->topic_image_upload_path = $this->current_volume_path_upload . config ( 'constants.TOPIC_IMAGE_IMAGE' );
		$this->topic_video_upload_path = $this->current_volume_path_upload . config ( 'constants.TOPIC_VIDEO_IMAGE' );
		$this->profile_image_path = $this->current_volume_path . config ( 'constants.PROFILE_IMAGE' );
		$this->banner_attachment = $this->current_volume_path . config ( 'constants.BANNER_IMAGE' );
		$this->extra_attachment = $this->current_volume_path . config ( 'constants.EXTRA_IMAGE' );
		$this->product_attachment = $this->current_volume_path . config ( 'constants.PRODUCT_IMAGE' );
		$this->supplier_attachment = $this->current_volume_path . config ( 'constants.SUPPLIER_IMAGE' );
		$this->message_file_path = $this->current_volume_path_upload . config ( 'constants.MESSAGE_FILE' );

		$url = URL::to ( '/' );
		$this->data ['site_full_path'] = $url;
		$this->site_full_path = $url;
		$this->data ['admin_user_profile_image'] = $this->admin_user_attachment;
		$this->data ['page_condition'] = 'default';
	}
	public function checkemail($str) {
		return (! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str )) ? FALSE : TRUE;
	}
	public function loginstatus($loginstatus = 0) {
		$loginstatusarray = array (
				'Normal',
				'Google',
				'Apple',
				'Facebook',
				'Twitter',
				'Instagram'
		);
		return $loginstatusarray [$loginstatus];
	}
	public function _pre($all_array, $exit = false) {
		echo '<pre>';
		print_r ( $all_array );
		echo '</pre>';

		if($exit) exit('DEBUG!!!');
	}
	public function mime2ext($mime)
    {
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
        "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
        "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
        "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
        "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
        "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
        "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
        "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
        "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
        "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
        "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
        "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
        "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
        "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
        "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
        "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
        "pdf":["application\/pdf","application\/octet-stream"],
        "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
        "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
        "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
        "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
        "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
        "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
        "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
				"mkv":["video\/x-matroska"],
        "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
        "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
        "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
        "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
        "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
        "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
        "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
        "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
        "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
        "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
        "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
        "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
        "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
        "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
        "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
        "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
        "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
        "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
        "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
        "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);

        foreach($all_mimes as $key => $value)
        {
            $p = '';

            if(array_search($mime,$value) !==false):

                $imagearray = array('png','jpeg','bmp','gif','svg');
                $videoarray = array('wmv','ogg','3g2','3gp','mp4','f4v','flv','webm','mpeg','mov','avi','movie','rv','jp2', 'mkv');
                $audioarray = array('au','ac3','flac','ogg','m4a','aac','wav','mp3','mid','aif','ram','rpm','ra','wma');
                if(in_array($key,$imagearray)):
                    $result="image";
                elseif(in_array($key,$videoarray)):
                    $result="video";
                elseif(in_array($key,$audioarray)):
                    $result="audio";
                else:
                    $result="file";
                endif;
                return $result;
            endif;

        }
        return false;
    }
    public function checkmessageidfavourite($userid=null,$messageid=null)
    {
        $favmesaage = FavouriteMessages::where('userid',$userid)->where('messageid',$messageid)->count();
        return $favmesaage;
    }
    public function checkmessageidinspirational($userid=null,$messageid=null)
    {
        $inspirationalmesaage = InspirationalMessages::where('userid',$userid)->where('messageid',$messageid)->count();
        return $inspirationalmesaage;
    }
	public function send_email($subject, $Email_body_content, $to_email_address, $username, $attachment_1 = "", $attachment_2 = "") {
		$html_data = $Email_body_content;
		$from_email = "";
		$email_smtp_id = config ( 'constants.Email_Smtp_Id' );
		$email_smtp_detail = EmailSmtp::where ( 'id', '=', $email_smtp_id )->where ( 'deleted', '=', '0' )->first ();
		if (count ( $email_smtp_detail ) > 0) {
			$logo_path = URL::to ( '/' ) . '/images/logo.png';
			$port_number = $email_smtp_detail->smtp_port;
			$encryption_type = $email_smtp_detail->encryption_type;
			$smtp_host = $email_smtp_detail->smpt_host;
			$smtp_password = $email_smtp_detail->smtp_password;
			$smtp_user_name = $email_smtp_detail->smtp_username;
			$from_email = $email_smtp_detail->from_email;
			$from_name = $email_smtp_detail->from_name;

			config ( [
					'mail' => [
							'from' => [
									'address' => $from_email,
									'name' => $from_name
							],
							'host' => $smtp_host,
							'port' => $port_number,
							'username' => $smtp_user_name,
							'password' => $smtp_password,
							'encryption' => $encryption_type,
							'driver' => 'smtp'
					]
			] );

			$view = View::make ( 'emails.emailtemplate', [
					'site_title' => $this->data ['site_title'],
					'site_url' => $this->data ['site_full_path'],
					'site_email' => "",
					'site_phone' => "",
					'logo_path' => $logo_path,
					'Email_body_content' => $Email_body_content
			] );
			$html_data = $view->render ();
			try {
				Mail::send ( array (), array (), function ($message) use ($to_email_address, $username, $subject, $html_data, $attachment_1, $attachment_2) {
					$message->to ( $to_email_address, $username )->subject ( $subject );
					if ($attachment_1 != '') {
						$message->attach ( $attachment_1 );
					}
					if ($attachment_2 != '') {
						$message->attach ( $attachment_2 );
					}
					$message->setBody ( $html_data, 'text/html' );
				} );
				// Mail::to('developer@gmail.com')->send(new ExceptionOccured($html));
				/*
				 * $message = \Swift_Message::newInstance($subject)->setFrom([$from_email => $from_name])->setTo([$to_email_address => $username])->setBody($html_data, 'text/html');
				 * if($attachment_1 != ''){
				 * $message->attach( \Swift_Attachment::fromPath($attachment_1));
				 * }
				 * if($attachment_2 != ''){
				 * $message->attach( \Swift_Attachment::fromPath($attachment_2));
				 * }
				 */
				$message_responce = 1;
			} catch ( \Swift_TransportException $e ) {
				$message_responce = $e->getMessage ();
			}
		} else {
			$message_responce = "No Found SMTP";
		}
		$message_send_insert = new MessageSendHistories ();
		$message_send_insert->type = $subject;
		$message_send_insert->from_email = $from_email;
		$message_send_insert->to_email = $to_email_address;
		$message_send_insert->message_data = $html_data;
		$message_send_insert->message_responce = $message_responce;
		$message_send_insert->save ();
		return $message_responce;
	}
	public function getmodule() {
		$modulelist = Module::where ( 'deleted', '0' )->get ();
		return $modulelist;
	}
	public function getadminuser($id) {
		$memberDetails = User::where ( 'deleted', 0 )->where ( 'id', $id )->first ();
		return $memberDetails;
	}
	public function getalldropdown() {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		$genderList = array (
				'male',
				'female',
				'other'
		);
		$maritalstatusList = array (
				'Married',
				'Single',
				'Divorced'
		);
		$educationList = array (
				'Bsc',
				'Msc',
				'Bcom',
				'Mcom',
				'BE',
				'ME',
				'B.ed',
				'Bsc',
				'Msc'
		);
		$militarystatusList = array (
				'Army',
				'Navy',
				'Air Force',
				'Marine Corps',
				'Coast Guard'
		);
		$employmentList = array (
				'Permanent or fixed-term employees',
				'Casual employees',
				'Apprentices or trainees',
				'Employment agency staff',
				'Contractors and sub-contractors'
		);
		$dropdownlist = array (
				'genderList' => $genderList,
				'maritalstatusList' => $maritalstatusList,
				'educationList' => $educationList,
				'employmentList' => $employmentList,
				'militarystatusList' => $militarystatusList
		);
		$response ['success'] = true;
		$response ['message'] = 'Dropdown value get successfully.';
		$response ['data'] = $dropdownlist;
		return response ( $response, 200 );
	}

	public function getUserDetails($uid = null, $short_detail = NULL) {
		$memberDetails = false;
		if($uid) {
			if ($short_detail) {
				$arr__user_field = ['id','pairid', 'email', 'password', 'full_name', 'firstname', 'lastname',
				'mobile_number', 'gender', 'age', 'dateofbirth', 'marital_status',
				'education', 'military_status',
				'employment', 'short_detail', 'profile_photo', 'loginstatus'
		];
		$memberDetails = mobileapp::select($arr__user_field)
			->where ( 'deleted', 0 )->where ( 'id', $uid )->first ();
		$memberDetails ['profile_photo'] = ($memberDetails ['profile_photo']) ? url() . '/' . $memberDetails ['profile_photo'] : '';
		// $memberDetails = mobileapp::with ( $this->__withUser ( 'childrens' ) )->with ( $this->__withUser ( 'executors' ) )->with ( 'packageDetails' )->with ( 'giftPriceDetails' )->with ( 'userSetting' )->where ( 'deleted', 0 )->where ( 'id', $uid )->first ();
		$memberDetails ['loginstatus'] = $this->loginstatus ( $memberDetails ['loginstatus'] ) . ' Login';

			} else {
				$arr__user_field = ['id','pairid', 'email', 'password', 'full_name', 'firstname', 'lastname',
				'mobile_number', 'gender', 'age', 'dateofbirth', 'marital_status',
					'education', 'military_status',
					'employment', 'short_detail', 'profile_photo', 'loginstatus', 'customer_id', 'total_size', 'package'
			];
			$memberDetails = mobileapp::select($arr__user_field)
				->with ( $this->__withUser ( 'childrens' )
					)->with ( $this->__withUser ( 'executors' )
							)->with('packageDetails')->with('giftPriceDetails')->where ( 'deleted', 0 )->where ( 'id', $uid )->first ();
			$memberDetails ['profile_photo'] = ($memberDetails ['profile_photo']) ? url() . '/' . $memberDetails ['profile_photo'] : '';
			// $memberDetails = mobileapp::with ( $this->__withUser ( 'childrens' ) )->with ( $this->__withUser ( 'executors' ) )->with ( 'packageDetails' )->with ( 'giftPriceDetails' )->with ( 'userSetting' )->where ( 'deleted', 0 )->where ( 'id', $uid )->first ();
			$memberDetails ['loginstatus'] = $this->loginstatus ( $memberDetails ['loginstatus'] ) . ' Login';
			$user_setting = User_setting::where('user_id', $uid)->select('data')->first();
			$arr_user_setting = ['email_alert' => 0, 'push_notification' => 0];
			if (sizeof($user_setting) > 0) {
				$arr_user_setting = unserialize($user_setting->data);
			}

			$memberDetails['email_alert'] = $arr_user_setting['email_alert'];
			$memberDetails['push_notification'] = $arr_user_setting['push_notification'];// + $arr_user_setting;//['user_setting'];//['user_setting'];//[$arr__data, $arr_user_setting];//$memberDetails + $arr_user_setting;
			}

		}
		return $memberDetails;
	}

	public function setNotification($user_id, $msg, $type = 'message', $title = '', $key_id = null)
	{
	    NotificationAlert::insert(
					['notification' => $msg, 'userid' => $user_id, 'type' => $type, 'title' => $title, 'key_id' => $key_id]
					);
		$push_notification = $this->_checkUserNotification($user_id);
		if($push_notification) {
			

			$noeduser = mobileapp::where('id',$user_id)->first();

			if($noeduser->pairid !=""):

				$content = array("en" => $msg);
					//0bc46e63-89c7-4226-bf92-e1715f5492df

					//MjZlZTBmODctMjgzNy00MjExLTllZDgtMzJhZWJjMjY4ZDhj

				$fields = array(
						'app_id' => "0bc46e63-89c7-4226-bf92-e1715f5492df",
						//'included_segments' => array('All'),
						'include_player_ids' => array($noeduser->pairid),
						'data' => array("foo" => "bar"),
						'headings'=> array("en" => 'Key Moments'),
						'contents' => $content,
						'large_icon' =>"ic_launcher_round.png",
				);
				// echo '<pre>';
				//print_r ($fields);
				$fields = json_encode($fields);
				//print("\nJSON sent:\n");
				//print($fields);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic MjZlZTBmODctMjgzNy00MjExLTllZDgtMzJhZWJjMjY4ZDhj'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

				$response = curl_exec($ch);

				//echo $response;

				//echo "\n\n\n".curl_error($ch);

				curl_close($ch);
			endif;
		}
	}

	public function notificationList(Request $request)
    {
        $response ['success'] = '';
        $response['message'] = '';
        $response['data'] = (object)array();
        $userid =  $request->userid;
        if($userid == ""):
            $response ['success'] = false;
            $response['message'] = 'User Id Required.';
            return response($response, 200);
        else:
            $StudentCount = mobileapp::where('id',$userid)->count();
            if($StudentCount < 1):
                $response ['success'] = false;
                $response['message'] = 'Invalid User!';
                return response($response, 200);
            endif;
        endif;
        $notificationlist = NotificationAlert::select( "id", "title", "notification", "userid", "type", "isRead","created_at", "key_id")->where('userid',$userid)->orderBy('id', 'DESC')->get();
        if($notificationlist):
            foreach($notificationlist as $notificationlist_val):
                $notificationlist_val->notification_date = date('d-m-Y', strtotime($notificationlist_val->created_at));
								$notificationlist_val->notification_time = date('h:i A', strtotime($notificationlist_val->created_at));
						if($notificationlist_val->isRead == "New"):
								$notificationlist_val->isRead = false;
						else:
								$notificationlist_val->isRead = true;
						endif;
            endforeach;
        endif;
        $response ['success'] = true;
        $response['message'] = 'Notification List.';
        $response['data']->notificationlists = $notificationlist;
        return response($response, 200);
    }

	public function updateNotificationStatus(Request $request)
    {
        $response ['success'] = true;
        $response['message'] = '';
        $response['data'] = (object)array();
        $notificationid =  $request->notificationid;
				$userid = $request->userid;
        if($notificationid == ""):
            $response ['success'] = false;
            $response['message'] = 'Notification Id Required.';
            return response($response, 200);
        elseif($userid == ""):
					$response ['success'] = false;
					$response['message'] = 'User Id Required.';
					return response($response, 200);
				else:
            $NotificationCount = NotificationAlert::where('id',$notificationid)->count();
            if($NotificationCount < 1):
                $response ['success'] = true;
                $response['message'] = 'Invalid Notification Id!';
                return response($response, 200);
            endif;
        endif;
        $notifications = NotificationAlert::find($notificationid);

				if($notifications->userid != $userid) :
					$response ['success'] = false;
					$response['message'] = 'Invalid User Id!';
					return response($response, 200);
				endif;

        $notifications->isRead = 'Read';
        $notifications->save();
        $Notificationdetails = NotificationAlert::where('id',$notificationid)->first();
        $notifications = NotificationAlert::select( "id", "title", "notification", "userid", "type", "isRead")->where('isRead', 'New')->where('userid',$Notificationdetails->userid)->where('deleted','0')->orderBy('id','DESC')->get();
        if($notifications):
            foreach($notifications as $notificationlist_val):
							if($notificationlist_val->isRead == "New"):
								$notificationlist_val->isRead = false;
							else:
									$notificationlist_val->isRead = true;
							endif;
            endforeach;
        endif;
        $finalnotifications = (object)$notifications;
        $response ['success'] = true;
        $response['message'] = 'Successfully changed status.';
        $response['data']->notificationslist = $finalnotifications;
        return response($response, 200);
    }

		public function updateUserNotificationStatus(Request $request)
    {
        $response ['success'] = true;
        $response['message'] = '';
        $response['data'] = (object)array();

				$userid = $request->userid;
				if($userid == ""):
					$response ['success'] = false;
					$response['message'] = 'User Id Required.';
					return response($response, 200);
				else:
            $NotificationCount = NotificationAlert::where('userid', $userid)->count();
            if($NotificationCount > 0):
             #   $response ['success'] = false;
             #   $response['message'] = 'No Unread Notification to update';
                #return response($response, 200);
						#else :
								$notifications = NotificationAlert::where('userid', $userid)->where('isRead', 'New')
																->update(['isRead' => 'Read']);
            endif;
        endif;

			  $notifications = NotificationAlert::select( "id", "title", "notification", "userid", "type", "isRead")->where('isRead', 'New')->where('userid',$userid)->where('deleted','0')->orderBy('id','DESC')->get();
        if($notifications):
            foreach($notifications as $notificationlist_val):
							if($notificationlist_val->isRead == "New"):
								$notificationlist_val->isRead = false;
							else:
									$notificationlist_val->isRead = true;
							endif;
            endforeach;
        endif;
        $finalnotifications = (object)$notifications;
        $response ['success'] = true;
        $response['message'] = 'Successfully changed status.';
        $response['data']->notificationslist = $finalnotifications;
        return response($response, 200);
    }
		public function getNewNotification(Request $request) {
			$response ['success'] = '';
			$response ['message'] = '';
			$response ['data'] = ( object ) array ();
			if (trim($request->userid) == "") :
				$response ['success'] = false;
				$response ['message'] = 'User id can not blank.';
				return response ( $response, 200 );
			endif;
			$userid = $request->userid;
			$cnt = NotificationAlert::where('userid', $userid)->where('isRead', 'New')->count();
			if($cnt > 0):
				$response ['success'] = true;
				$response ['message'] = 'Your Notification count successfully.';
				$response ['data']->count = $cnt;
				return response ( $response, 200 );
			else:
				$response ['success'] = true;
				$response ['message'] = 'Your Notification count successfully.';
				$response ['data']->count = 0;
				return response ( $response, 200 );
			endif;
	}

	/**
	 * Undocumented function
	 *
	 * @param [int] $userid
	 * @param [int] $messl
	 * @return void
	 */
	public function _countUnreadMessage($userid, $messageid, $type = 'message'){
		$cnt = 10;
		switch($type) {
			case 'message':
				$cnt = UnreadUserMessage::where('userid', $userid)->where('messageid', $messageid)->count();
				break;
			case 'schedule':
				$cnt = UnreadUserMessage::where('userid', $userid)->where('scheduleid', $messageid)->count();
				break;
		}
		return $cnt;
	}

	public function _format_schmessage($msglist, $userid) {

		if($msglist):
		    /*echo '<pre>';
		    print_r($msglist);
		    echo '</pre>';*/
			foreach($msglist as $msgl):

				$dt = $msgl->schedule_date;
				$msgl->schedule_date = date('d-m-Y', $dt);
				$msgl->schedule_time = date('h:i A', $dt);
				$msgl->userDetails = $this->getUserDetails($msgl->userid, true);

					$tageuser = array();
					if($msgl->tagged_users):
							$tagusers = $msgl->tagged_users;
							unset($msgl->tagged_users);
							foreach($tagusers as $taged):
											array_push($tageuser,$taged->userid);
							endforeach;
									$userlist = mobileapp::select(['id','pairid', 'email', 'password', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender','age','dateofbirth','marital_status', 'children', 'education', 'military_status', 'employment', 'list_of_executors', 'package', 'short_detail', 'profile_photo'])->whereIn('id',$tageuser)->get();
									if($userlist):
											foreach($userlist as $users):
													if($users->profile_photo !=""):
															$users->profile_photo = url().'/'.$users->profile_photo;
													else:
															$users->profile_photo = "";
													endif;

											endforeach;
									endif;

							$msgl->tagged_users = $userlist;

					endif;
					$msgl->unread = $this->_countUnreadMessage($userid, $msgl->id, 'schedule');


            if($msgl->messages):
							#$msgl->unread = 111;//$this->_countUnreadMessage($userid, $msgl->id);
            foreach($msgl->messages as $messl):
                if($messl->messagetype == 'text'):
                    $messl->message = $messl->message;
                else:
                    $messl->message = url().'/public/uploads/message_files/'.$messl->message;
                endif;

                $userdetails = mobileapp::select(['id','pairid', 'email', 'password', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender','age','dateofbirth','marital_status', 'children', 'education', 'military_status', 'employment', 'list_of_executors', 'package', 'short_detail', 'profile_photo'])->where('id',$messl->userid)->first();
                if($userdetails->profile_photo !=""):
                    $userdetails->profile_photo = url().'/'.$userdetails->profile_photo;
                else:
                    $userdetails->profile_photo = "";
                endif;
                $messl->senderDetails = $userdetails;
                $favourite = $this->checkmessageidfavourite($userdetails->id,$messl->id);
                if($favourite > 0):
                    $messl->favourite = true;
                else:
                    $messl->favourite = false;
                endif;

                $inspirational = $this->checkmessageidinspirational($userdetails->id,$messl->id);
                if($inspirational > 0):
                    $messl->inspirational = true;
                else:
                    $messl->inspirational = false;
                endif;
				$explod = explode(' ', $messl->created_at);
				//$messl->date =  $explod[0];
				//$messl->time =  $explod[1];
				$messl->date =  date('d-m-Y', strtotime($messl->created_at));
				$messl->time =  date('h:i A', strtotime($messl->created_at));
            endforeach;
        endif;

					#$msgl->schedule_date
			endforeach;

		endif;
		return $msglist;
	}
	function _checkUserNotification($user_id, $type = 'notification') {
		$user_setting = User_setting::where('user_id', $user_id)->first();
		$notification_status = false;
		$email_status = false;
		if(sizeof($user_setting) > 0) {
			$data = $user_setting->data;
			$data = unserialize($data);
			$notification_status_val = isset($data['push_notification']) ? $data['push_notification'] : 0;
			if($notification_status_val == 1){
				$notification_status = true;
			}
			$email_status_val = isset($data['email_alert']) ? $data['email_alert'] : 0;
			if($email_status_val == 1){
				$email_status = true;
			}
		}
		switch($type) {
			case 'notification':
				return $notification_status;
				break;
			case 'email':
				return $email_status;
				break;
		}
	}

	function updateUnread($scheduleid, $userid) {
		UnreadUserMessage::where('scheduleid', $scheduleid)->where('userid', $userid)->delete();
	}
	function __withUser($type) {
		switch ($type) {
			case 'childrens' :
				return array (
				'childrens' => function ($query) {
				$query->select ( 'id', 'userid', 'name', 'email', 'phone' )->where ( 'deleted', 0 );
				}
				);
				break;
			case 'executors' :
				return array (
				'executors' => function ($query) {
				$query->select ( 'id', 'user_id', 'name', 'email', 'phone' );
				}
				);
				break;
		}
	}

	function _fileSizeKB() {
		$gb = 1;
		$mb = $gb * 1000;
		$kb = $mb * 1000;
		$byte = $kb * 1000; // kb
		$kb = 1000; // MB
		$mb = 1000;
		$gb = 1000 * $mb; // 1000 MB 1GB
	}
}
