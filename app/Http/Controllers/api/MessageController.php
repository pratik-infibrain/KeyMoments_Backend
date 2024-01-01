<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Package;
use App\Userpackage;
use App\mobileapp;
use App\Giftsoldier;
use App\MediaFile;
use App\ScheduleMessage;
use App\Keymoment;
use File, DB;
use App\MessageTaggedUser;
use App\FavouriteMessages;
use App\Allmessage;
use App\InspirationalMessages;
use App\UnreadUserMessage;

class MessageController extends CommonController
{
	public $uid = '';
	public $messageid = '';
	public function countpages($path) {
		$pdf = file_get_contents ( $path );
		$number = preg_match_all ( "/\/Page\W/", $pdf, $dummy );
		return $number;
	}
	public function messageList(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		// $request->userid
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'user id can not blank.';
			return response ( $response, 200 );
		endif;
		$userid = $request->userid;
		$msglist = $this->getUserMessage($userid);

		if (sizeof ( $msglist ) > 0) :
			$response ['success'] = true;
			$response ['message'] = 'Message list successfully get.';
			$response ['data']->messageList = $msglist;
		 else :
			$response ['success'] = false;
			$response ['message'] = 'no records found.';
		endif;
		return response ( $response, 200 );
	}

	/**
	 *
	 * @param unknown $id
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function createSchedule(Request $request)
	{
	    //exit;
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'user id can not blank.';
			return response ( $response, 200 );
		endif;
		$files = $request->file('media_files');
		if(!empty($files))
		{
			$chk_files = $files;
			$size = 0;
			foreach($chk_files as $f){
				$size += $f->getSize();
			}
			$remain_size = $this->_checkUserSpace($request->userid);
			if ($size > $remain_size) {
				$response ['success'] = false;
				$response ['message'] = 'You have not enough space to upload files.';
				return response ( $response, 200 );
			}
		}
/*if($files = $request->file('media_files')) {
			$response ['success'] = false;
			$response ['message'] = 'totoal files .' . count($files);
		}
		return response($response, 200);*/
		/*
		if (trim ( $request->to_userid ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Schedule to user id can not blank.';
			return response ( $response, 200 );
		endif;*/

		if (empty ( $request->tagged_userid )) :
			$response ['success'] = false;
			$response ['message'] = 'Schedule to tagged user id can not blank.';
			return response ( $response, 200 );
		endif;

		$arr_tagged_userid = $request->tagged_userid;
		$arr_tagged_userid = str_replace("[", "", $request->tagged_userid);
		$arr_tagged_userid = str_replace("]", "", $arr_tagged_userid);
		$arr_tagged_userid = explode(",", $arr_tagged_userid);

		if (trim ( $request->schedule_date ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Schedule Date can not blank.';
			return response ( $response, 200 );
		endif;

		if (trim ( $request->key_id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Keymoment id can not blank.';
			return response ( $response, 200 );
		endif;

		if (trim ( $request->message ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Message can not blank.';
			return response ( $response, 200 );
		endif;

		// schedule_messages

		$schedule_date = strtotime($request->schedule_date);
		$scheduleMessage = new ScheduleMessage();
		$scheduleMessage->userid = $request->userid;
		#$scheduleMessage->to_userid = $request->to_userid;
		#echo $schedule_date . ' ' . $request->schedule_date . ' = ' . '2022-01-25 22:30:00';
		$scheduleMessage->schedule_date = $schedule_date;
		$scheduleMessage->key_id = $request->key_id;
		$scheduleMessage->message = $request->message;
		$scheduleMessage->save();

		if(!empty($files))
		{
		    $count = 1;
			foreach($files as $file)
			{
				$msg_file = new MediaFile();
				$image = $file;//$request->file('image');
				#$response['data']->mime[] = $image;
				$filesize = $image->getSize();
				// Store file size
				$msg_file->filesize = $filesize;

				$org_name = $image->getClientOriginalName();
				//$mime = mime_content_type($file);
				$imageName = $count.'_'.time().'.'.$image->getClientOriginalExtension();
				$destinationPath = $this->message_file_path;
				$docuemntfileurl = '/' . $destinationPath . $imageName;
				$mv = $image->move($destinationPath,  $imageName);
				//
				#$response ['data']->name[] = $name;
				$msg_file->userid = $request->userid;
				$msg_file->filename = $org_name;
				$msg_file->type_id = $scheduleMessage->id;
				$msg_file->filepath = $docuemntfileurl;//$destinationPath . $org_name;
				//$po_file->image = $imageName;
				$this->_updateUserSpace($filesize, $request->userid);


				//if(strstr($mime, "video/")){
					// this code for video
				//	$msg_file->filetype = 'video';
				//}else if(strstr($mime, "image/")){
					// this code for image
				//	$msg_file->filetype = 'image';
				//}


				$msg_file->type = 'schedule_message';
				$msg_file->save();
				$count++;
			}
		}
		#$response['data']->tag = $arr_tagged_userid;
		foreach ($arr_tagged_userid as $uid) {
			$message_tagged_userid = new MessageTaggedUser();
			$message_tagged_userid->userid = $uid;
			$message_tagged_userid->messageid = $scheduleMessage->id;
			$message_tagged_userid->save();
		}
		//$arr_tagged_userid
		//$scheduleMessage->id;
		// Set notifictaion
		$this->_setNotification($scheduleMessage, 'new_schedule', '', $scheduleMessage->id);
		// End Set notification
		$userid = $request->userid;
		$msglist = $this->getUserMessage($userid);
		/*
		$msglist = ScheduleMessage::select (  'id', 'userid', 'to_userid', 'key_id', 'message', 'read_status', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))
		->with ( array (
				'media_files' => function ($q) {
					$q->select (  'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
		) )->with('keymoment')->where ( 'userid', $request->userid )->get ();*/
		if (sizeof ( $msglist ) > 0) :
			foreach ($msglist as $msgkey => $msgvalue) {
				//$response ['data']->$msgkey = $msgvalue;
			}
			$response ['success'] = true;
			$response ['message'] = 'Created schedule successfully.';
			//$response ['data']->messageList = $msglist;
		else :
			$response ['success'] = false;
			$response ['message'] = 'no records found.';
		endif;
		return response ( $response, 200 );
	}

	public function updateSchedule(Request $request)
	{
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'id can not blank.';
			return response ( $response, 200 );
		endif;
		$id = $request->id;
		if (trim ( $request->userid ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'user id can not blank.';
			return response ( $response, 200 );

		endif;


		if (trim ( $request->schedule_date ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Schedule Date can not blank.';
			return response ( $response, 200 );
		endif;

		if (trim ( $request->key_id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Keymoment id can not blank.';
			return response ( $response, 200 );
		endif;

		if (trim ( $request->message ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Message can not blank.';
			return response ( $response, 200 );
		endif;

		// schedule_messages
		$schedule_date = strtotime($request->schedule_date);
		$scheduleMessage = ScheduleMessage::find($id);
		$scheduleMessage->userid = $request->userid;
		//$scheduleMessage->to_userid = $request->to_userid;
		$scheduleMessage->schedule_date = $schedule_date;
		$scheduleMessage->key_id = $request->key_id;
		$scheduleMessage->message = $request->message;
		$scheduleMessage->save();
		$files = $request->file('media_files');
		if(!empty($files))
		{
		    $count = 1;
			foreach($files as $file)
			{
				$msg_file = new MediaFile();
				$image = $file;//$request->file('image');
				#$response['data']->mime[] = $image;
				$org_name = $image->getClientOriginalName();
				//$mime = mime_content_type($file);
				$imageName = $count.'_'.time().'.'.$image->getClientOriginalExtension();
				$destinationPath = $this->message_file_path;
				$docuemntfileurl = '/' . $destinationPath . $imageName;
				$mv = $image->move($destinationPath,  $imageName);
				//
				#$response ['data']->name[] = $name;
				$msg_file->userid = $request->userid;
				$msg_file->filename = $org_name;
				$msg_file->type_id = $scheduleMessage->id;
				$msg_file->filepath = $docuemntfileurl;//$destinationPath . $org_name;
				//$po_file->image = $imageName;
				//if(strstr($mime, "video/")){
				// this code for video
				//	$msg_file->filetype = 'video';
				//}else if(strstr($mime, "image/")){
				// this code for image
					//	$msg_file->filetype = 'image';
					//}

					$msg_file->type = 'schedule_message';
					$msg_file->save();
					$count++;
				}
		}
		$userid = $request->userid;
		$msglist = $this->getUserMessage($userid);
		/*
		$msglist = ScheduleMessage::select (  'id', 'userid', 'to_userid', 'key_id', 'message', 'read_status', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select (  'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->where ( 'userid', $request->userid )->get ();*/
		if (sizeof ( $msglist ) > 0) :
			#foreach ($msglist as $msgkey => $msgvalue) {
			//$response ['data']->$msgkey = $msgvalue;
			#}
			$response ['success'] = true;
			$response ['message'] = 'Created schedule successfully.';
			$response ['data']->messageList = $msglist;
		else :
			$response ['success'] = false;
			$response ['message'] = 'no records found.';
		endif;
		return response ( $response, 200 );

	}

	public function deleteScheduleMessage(Request $request)
	{
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->scheduleid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Schedule Message id can not blank.';
			return response ( $response, 200 );
		endif;

		$id = $request->scheduleid;
		$schedule_message = ScheduleMessage::find($id);
		$schedule_message->deleted = '1';
		$schedule_message->save();
		$userid = $schedule_message->userid;
		$msglist = $this->getUserMessage($userid);
		if ( sizeof($msglist) > 0 ) :
			$response ['success'] = true;
			$response ['message'] = 'Keymoment Schedule deleted successfully.';
			//$response ['data']->messageList = $msglist;
		else :
			$response ['success'] = true;
			$response ['message'] = 'Keymoment Schedule deleted successfully.';
		endif;

		return response ( $response, 200 );

	}

	private function getUserMessage($userid, $includeTagged = null)
	{
		$this->uid = $userid;
		if($includeTagged) :
			$messageids = MessageTaggedUser::where('userid', $userid)->select('messageid', 'userid')->get();
			$messageid = array();
			foreach($messageids as $mess):
				array_push($messageid, $mess->messageid);
			endforeach;
			$this->messageid = $messageid;

			$msglist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->latest()->limit(1);
				}))->where(function ($query) {
					$query->where('userid', $this->uid)->orWhereIn('id', $this->messageid);
				})->where('archive', 0)->where('deleted','0')->orderBy('id','DESC')->get ();
			//->whereIn('id',$tageuser)

		else :
	    	$msglist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->latest()->limit(1);
					}))->where( 'userid', $userid )->where('archive', 0)->where('deleted','0')->orderBy('id','DESC')->get ();

	endif;
	$msglist = $this->_format_schmessage($msglist, $userid);
	return $msglist;


		//return $msglist;
	}
private function getKeymomentUserMessage($userid, $includeTagged = null, $request)
	{
		$this->uid = $userid;
		if($includeTagged) :
			$messageids = MessageTaggedUser::where('userid', $userid)->select('messageid', 'userid')->get();
			$messageid = array();
			foreach($messageids as $mess):
				array_push($messageid, $mess->messageid);
			endforeach;
			$this->messageid = $messageid;

			$msglist = ScheduleMessage::select ('id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->latest()->limit(1);
				}))->whereIn('id', $this->messageid)->where('archive', 0)->where('deleted','0')->orderBy('id','DESC');//->get();
			//->whereIn('id',$tageuser)

		else :
	    	$msglist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->latest()->limit(1);
					}))->where( 'userid', $userid )->where('archive', 0)->where('deleted','0')->orderBy('id','DESC');//->get ();

	endif;
	$perpage = $request->perpage;
	$pagenumber = $request->pagenumber;
	if($perpage > 0):
		if($pagenumber > 1):
				$limit = $perpage;
				$offset = ($limit*$pagenumber)-$limit;
		else:
				$limit = $perpage;
				$offset = '0';
		endif;
	endif;
	if($perpage > 0):
		$msglist = $msglist->offset($offset)->limit($limit);
	endif;
		$msglist = $msglist->get();

	$msglist = $this->_format_schmessage($msglist, $userid);
	return $msglist;


		//return $msglist;
	}

	public function ArchivespecificeMessage(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = (object)array();
		if (trim($request->messageid) == "") :
			$response['success'] = false;
			$response['message'] = 'Message id can not blank.';
			return response ( $response, 200 );
		else:
		    $schedule_message1 = Allmessage::where('id',$request->messageid)->count();
		    if($schedule_message1 > 0):
		        $schedule_message = Allmessage::find($request->messageid);
		        $schedule_message->isachived = '1';
		        $schedule_message->save();
		        $response['success'] = true;
			    $response['message'] = 'Your Message successfully achived.';
			    return response($response, 200);
		    else:
		        $response['success'] = false;
			    $response['message'] = 'Message id invalid.';
			    return response($response, 200);
		    endif;
		endif;
	}

	public function deleteArchivespecificeMessage(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = (object)array();
		if (trim($request->messageid) == "") :
			$response['success'] = false;
			$response['message'] = 'Message id can not blank.';
			return response ( $response, 200 );
		else:
		    $schedule_message1 = Allmessage::where('id',$request->messageid)->count();
		    if($schedule_message1 > 0):
		        $schedule_message = Allmessage::find($request->messageid);
		        $schedule_message->isachived = '0';
		        $schedule_message->save();
		        $response['success'] = true;
			    $response['message'] = 'Your Message successfully deleted achived.';
			    return response($response, 200);
		    else:
		        $response['success'] = false;
			    $response['message'] = 'Message id invalid.';
			    return response($response, 200);
		    endif;
		endif;
	}
	public function  getArchiveSpecificeMessage(Request $request)
	{
	    $response['success'] = '';
		$response['message'] = '';
		$response['data'] = ( object ) array ();
		$userid = $request->userid;
		if (trim($request->userid) == "") :
			$response['success'] = false;
			$response['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		else:
		    $usercheck = mobileapp::where('id',$request->userid)->count();
		    if($usercheck > 0):

		        $messgelist = Allmessage::where('deleted','=','0')->where(function ($query) use ($userid) {
             		 $query->where('userid', '=', $userid)
                    ->orWhere('usertoid', '=', $userid);
				})->where('isachived','=','1')->orderBy('id','DESC')->orderby('isread','ASC')->get();

                if($messgelist):
                    foreach($messgelist as $messl):
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
                        $messl->unread = 111;
        				$explod = explode(' ', $messl->created_at);
        				//$messl->date =  $explod[0];
                        //$messl->time =  $explod[1];
        				$messl->date =  date('d-m-Y', strtotime($messl->created_at));
        				$messl->time =  date('h:i A', strtotime($messl->created_at));
                    endforeach;
                endif;
                $reversed = $messgelist->reverse();
				$reversed->all();

                $response['success'] = true;
			    $response['message'] = 'Archived message list.';
			    $response['data']->archivedMessageList = $reversed;
			    return response ( $response, 200 );
		    else:
		        $response['success'] = false;
			    $response['message'] = 'User id invalid.';
			    return response ( $response, 200 );
		    endif;
		endif;
	}
	public function getArchiveMessage(Request $request)
	{
	    $response['success'] = '';
		$response['message'] = '';
		$response['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response['success'] = false;
			$response['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		else:
		    $usercheck = mobileapp::where('id',$request->userid)->count();
		    if($usercheck > 0):
		        $Schedulemessagelist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->where('isachived','1');
					}))->where( 'userid', $request->userid )->where('archive', '1')->where('deleted','0')->orderBy('id','DESC')->get ();

                $response['success'] = true;
			    $response['message'] = 'Archived message list.';
			    if($Schedulemessagelist):
			        foreach($Schedulemessagelist as $schedulemessage):
			            $dt = $schedulemessage->schedule_date;
				$schedulemessage->schedule_date = date('d-m-Y', $dt);
				$schedulemessage->schedule_time = date('h:i A', $dt);
                		if($schedulemessage->messages):
            foreach($schedulemessage->messages as $messl):
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
                $messl->unread = 111;
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
			        endforeach;
			    endif;
			    $response['data']->archivedScheduleList = $Schedulemessagelist;
			    return response ( $response, 200 );
		    else:
		        $response['success'] = false;
			    $response['message'] = 'User id invalid.';
			    return response ( $response, 200 );
		    endif;
		endif;
	}
	public function ArchiveMessage(Request $request)
	{
	    $response['success'] = '';
		$response['message'] = '';
		$response['data'] = ( object ) array ();
		if (trim($request->scheduleid) == "") :
			$response['success'] = false;
			$response['message'] = 'Schedule id can not blank.';
			return response ( $response, 200 );
		else:
		    $schedule_message1 = ScheduleMessage::where('id',$request->scheduleid)->count();
		    if($schedule_message1 > 0):
		        $schedule_message = ScheduleMessage::find($request->scheduleid);
		        $schedule_message->archive = '1';
		        $schedule_message->save();

		        $schedule_message_all = Allmessage::where('scheduleid', $request->scheduleid)->update(array('isachived' => '1'));

		        $response['success'] = true;
			    $response['message'] = 'Your schedule successfully achived.';
			    return response ( $response, 200 );
		    else:
		        $response['success'] = false;
			    $response['message'] = 'Schedule id invalid.';
			    return response ( $response, 200 );
		    endif;
		endif;
	}
	public function deleteArchiveMessage(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = (object)array();
		if (trim($request->scheduleid) == "") :
			$response['success'] = false;
			$response['message'] = 'Schedule id can not blank.';
			return response ( $response, 200 );
		else:
		    $schedule_message1 = ScheduleMessage::where('id',$request->scheduleid)->count();
		    if($schedule_message1 > 0):

		        $schedule_message = ScheduleMessage::find($request->scheduleid);
		        $schedule_message->archive = '0';
		        $schedule_message->save();

		        $schedule_message_all = Allmessage::where('scheduleid', $request->scheduleid)->update(array('isachived' => '0'));

		        $response['success'] = true;
			    $response['message'] = 'Your schedule successfully deleted achived.';
			    return response($response, 200);
		    else:
		        $response['success'] = false;
			    $response['message'] = 'Schedule id invalid.';
			    return response($response, 200);
		    endif;
		endif;
	}
	public function TagMessageList(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		else:
		    $schedule_message1 = mobileapp::where('id',$request->userid)->count();
		    if($schedule_message1 > 0):
		        $messageids = MessageTaggedUser::where('userid',$request->userid)->get();
		        if(count($messageids)>0):
		            $messageid = array();
		            foreach($messageids as $mess):
		                array_push($messageid,$mess->messageid);
		            endforeach;

		            $messagelist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date', DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))->with ( array (
				'media_files' => function ($q) {
				$q->select (  'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')->whereIn ( 'id', $messageid )->where('archive', 0)->where('deleted','0')->get ();
	            $response ['success'] = true;
			    $response ['message'] = 'Message list successfully get.';
			    if($messagelist):
			        foreach($messagelist as $schedulemessage):
			             $dt = $schedulemessage->schedule_date;
				$schedulemessage->schedule_date = date('d-m-Y', $dt);
				$schedulemessage->schedule_time = date('h:i A', $dt);
			     endforeach;
			 endif;


			    $response ['data']->tagMessageList = $messagelist;
			    return response ( $response, 200 );
		        else:
		            $response ['success'] = false;
			        $response ['message'] = 'Message not found.';
			        return response ( $response, 200 );
		        endif;

		    else:
		        $response ['success'] = false;
			    $response ['message'] = 'User id invalid.';
			    return response ( $response, 200 );
		    endif;
		endif;

	}
	public function sendMessage(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
    endif;
        $schedule_message1 = mobileapp::where('id',$request->userid)->count();
	    if($schedule_message1 > 0):
	        if(trim($request->scheduleid) !=""):
	            $schedule_message2 = ScheduleMessage::where('id',$request->scheduleid)->count();
		        if($schedule_message2 > 0):
							$sc = ScheduleMessage::find($request->scheduleid);
		            $files = $request->file('document');
		            if(!empty($files)):
									$chk_files = $files;
									$size = 0;
									foreach($chk_files as $f){
										$size += $f->getSize();
									}
									$remain_size = $this->_checkUserSpace($request->userid);
									if ($size > $remain_size) {
										$response ['success'] = false;
										$response ['message'] = 'You have not enough space to upload files.';
										return response ( $response, 200 );
									}

            		    $count = 1;
            			foreach($files as $file):
										$msg_file = new MediaFile();
            				$image = $file;//$request->file('image');
            				#$response['data']->mime[] = $image;
            			  $filetype = $this->mime2ext($image->getClientmimeType());

            				$org_name = $image->getClientOriginalName();
            				//$mime = mime_content_type($file);
            				$imageName = $count.'_'.time().'.'.$image->getClientOriginalExtension();
            				$destinationPath = $this->message_file_path;
            				$docuemntfileurl = '/' . $destinationPath . $imageName;

										// Store file size
										$filesize = $image->getSize();

            				$mv = $image->move($destinationPath,  $imageName);

            				$insertmessage = new Allmessage();
										$insertmessage->userid = $request->userid;
										$insertmessage->scheduleid = $request->scheduleid;
										$insertmessage->message = $imageName;
										$insertmessage->messagetype = $filetype;
										$insertmessage->filesize = $filesize;
										$insertmessage->created_at = date('Y-m-d H:i:s');
										$insertmessage->save();
										$this->_updateUserSpace($filesize, $request->userid);
										$arr = ['sch_userid' => $sc->userid, 'userid' => $request->userid,
										'messageid' => $insertmessage->id, 'scheduleid' => $request->scheduleid];
										$this->_addUnreadUserMessage($arr);

										// Set notifictaion
										$this->_setNotification($sc, 'schedule', $request->userid, $sc->id);
										// End Set notification

            				$count++;
            			endforeach;
            		else:
    		            $insertmessage = new Allmessage();
    		            $insertmessage->userid = $request->userid;
    		            $insertmessage->scheduleid = $request->scheduleid;
    		            $insertmessage->message = $request->message;
    		            $insertmessage->messagetype = 'text';
										$insertmessage->created_at = date('Y-m-d H:i:s');
    		            $insertmessage->save();

										$arr = ['sch_userid' => $sc->userid, 'userid' => $request->userid,
										'messageid' => $insertmessage->id, 'scheduleid' => $request->scheduleid];
										$this->_addUnreadUserMessage($arr);

										// Set notifictaion
										$this->_setNotification($sc, 'schedule', $request->userid, $sc->id);
										// End Set notification
    		        endif;


    		        $messagelist = $this->getAllScheduleMessages($request->scheduleid, 1);
		            $response ['success'] = true;
			        $response ['message'] = 'Message send successfuly.';
			        $response ['data']->messageList = $messagelist;
			        return response ( $response, 200 );
		        else:
		            $response ['success'] = false;
			        $response ['message'] = 'Schedule id invalid.';
			        return response ( $response, 200 );
		        endif;
	        else:
    	        $schedule_message2 = mobileapp::where('id',$request->usertoid)->count();
    	        if($schedule_message2 > 0):
    	            $files = $request->file('document');
		            if(!empty($files)):
									$chk_files = $files;
									$size = 0;
									foreach($chk_files as $f){
										$size += $f->getSize();
									}
									$remain_size = $this->_checkUserSpace($request->userid);
									if ($size > $remain_size) {
										$response ['success'] = false;
										$response ['message'] = 'You have not enough space to upload files.';
										return response ( $response, 200 );
									}
            		    $count = 1;
            			foreach($files as $file):            				$msg_file = new MediaFile();
            				$image = $file;//$request->file('image');
            				#$response['data']->mime[] = $image;

            			    $filetype = $this->mime2ext($image->getClientmimeType());

            				$org_name = $image->getClientOriginalName();
            				//$mime = mime_content_type($file);
            				$imageName = $count.'_'.time().'.'.$image->getClientOriginalExtension();
            				$destinationPath = $this->message_file_path;
            				$docuemntfileurl = '/' . $destinationPath . $imageName;
										// Store file size
										$filesize = $image->getSize();

            				$mv = $image->move($destinationPath,  $imageName);

            				$insertmessage = new Allmessage();
    		                $insertmessage->userid = $request->userid;
    		                $insertmessage->usertoid = $request->usertoid;
    		                $insertmessage->message = $imageName;
    		                $insertmessage->messagetype = $filetype;
												$insertmessage->filesize = $filesize;
												$insertmessage->created_at = date('Y-m-d H:i:s');
    		                $insertmessage->save();
												$this->_updateUserSpace($filesize, $request->userid);
												$arr = ['userid' => $request->usertoid, 'messageid' => $insertmessage->id];
												$this->_addUnreadUserMessage($arr);

												$this->_setNotification($request->usertoid, 'message', $request->userid);
            				$count++;
            			endforeach;
            		else:
        	            $insertmessage = new Allmessage();
        	            $insertmessage->userid = $request->userid;
        	            $insertmessage->usertoid = $request->usertoid;
        	            $insertmessage->message = $request->message;
        	            $insertmessage->messagetype = 'text';
											$insertmessage->created_at = date('Y-m-d H:i:s');
        	            $insertmessage->save();

											$arr = ['userid' => $request->usertoid, 'messageid' => $insertmessage->id];
											$this->_addUnreadUserMessage($arr);
											$this->_setNotification($request->usertoid, 'message', $request->userid);
        	        endif;

        	      $messagelist = $this->getAllMessages($request->userid,$request->usertoid, 1);
    	          $response ['success'] = true;
    		        $response ['message'] = 'Message send successfuly.';
    		        $response ['data']->messageList = $messagelist;
    		        return response ( $response, 200 );
    	        else:
    	            $response ['success'] = false;
    		        $response ['message'] = 'User to id invalid.';
    		        return response ( $response, 200 );
    	        endif;
    	    endif;
	    else:
	        $response ['success'] = false;
		    $response ['message'] = 'User id invalid.';
		    return response ( $response, 200 );
	    endif;
    }
		public function _addUnreadUserMessage($data) {

			if (isset($data['scheduleid'])) {
				$sch_data = $data;
				//$sch_data['scheduleid'];
				$ii = true;
				$tagged_users = MessageTaggedUser::select('userid')->where('messageid', $data['scheduleid'])->get();

				if(count($tagged_users) > 0) {
					unset($sch_data['sch_userid']);
					foreach($tagged_users as $user) {
						if($data['userid'] != $user->userid) {
							$sch_data['userid'] = $user->userid;
							$unreadUM = new UnreadUserMessage();
							$unreadUM->messageid = $sch_data['messageid']	;
							$unreadUM->scheduleid = $sch_data['scheduleid']	;
							$unreadUM->userid = $sch_data['userid'];
							$unreadUM->save();
						} else {
							if($data['sch_userid'] != $user->userid && $ii){
								$ii = false;
								$sch_data['userid'] = $data['sch_userid'];
								$unreadUM = new UnreadUserMessage();
								$unreadUM->messageid = $sch_data['messageid']	;
								$unreadUM->scheduleid = $sch_data['scheduleid']	;
								$unreadUM->userid = $sch_data['userid'];
								$unreadUM->save();
							}
						}
					}
				}
			} else {
				//print_r($data);

				$unreadUM = new UnreadUserMessage();
				$unreadUM->messageid = $data['messageid']	;
				$unreadUM->userid = $data['userid'];
				$unreadUM->save();//create($data);
			}

			//
		}
		public function _getAllScheduleMessages(Request $request) {
			$response ['success'] = '';
			$response ['message'] = '';
			$response ['data'] = ( object ) array ();
			if (trim($request->scheduleid) == "") :
				$response ['success'] = false;
				$response ['message'] = 'Schedule id can not blank.';
				return response ( $response, 200 );
			endif;
			if (trim($request->userid) == "") :
				$response ['success'] = false;
				$response ['message'] = 'User id can not blank.';
				return response ( $response, 200 );
			endif;
				#$this->updateUnread($request->scheduleid, $request->userid);
				UnreadUserMessage::where('scheduleid', $request->scheduleid)->where('userid', $request->userid)->delete();
			$msg = $this->getAllScheduleMessages($request->scheduleid, $request->perpage, $request->pagenumber);
			$response ['success'] = true;
			$response ['message'] = 'Schedule\'s Message list successfuly get.';
			$response ['data']->messageList = $msg;
			return response ( $response, 200 );
		}

		public function _getAllMessages(Request $request) {
			$response ['success'] = '';
			$response ['message'] = '';
			$response ['data'] = ( object ) array ();
			if (trim($request->userid) == "") :
				$response ['success'] = false;
				$response ['message'] = 'User id can not blank.';
				return response ( $response, 200 );
			endif;
			if (trim($request->usertoid) == "") :
				$response ['success'] = false;
				$response ['message'] = 'User to id can not blank.';
				return response ( $response, 200 );
			endif;

			$msg = $this->getAllMessages($request->userid, $request->usertoid, $request->perpage, $request->pagenumber);
			$response ['success'] = true;
			$response ['message'] = 'Message list successfuly get.';
			$response ['data']->messageList = $msg;
			return response ( $response, 200 );
		}

    public function getAllScheduleMessages($scheduleid, $perpage = null, $pagenumber = null)
    {
			if($perpage > 0):
				if($pagenumber > 1):
						$limit = $perpage;
						$offset = ($limit*$pagenumber)-$limit;
				else:
						$limit = $perpage;
						$offset = '0';
				endif;
			endif;
        $messgelist = Allmessage::where('deleted','=','0')
				->where('scheduleid','=',$scheduleid)->orderby('isread','ASC')
				->orderBy('id','DESC');

				if($perpage > 0):
					$messgelist = $messgelist->offset($offset)->limit($limit);
				endif;
					$messgelist = $messgelist->get();
        if($messgelist):
            foreach($messgelist as $messl):

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
                 $messl->unread = 111;
				$explod = explode(' ', $messl->created_at);
				//$messl->date =  $explod[0];
				//$messl->time =  $explod[1];
				$messl->date =  date('d-m-Y', strtotime($messl->created_at));
				$messl->time =  date('h:i A', strtotime($messl->created_at));
            endforeach;
        endif;

		$reversed = $messgelist->reverse();
		$reversed->all();
		return $reversed;

        //return $messgelist;
    }
    public function getAllMessages($userid, $usertoid, $perpage = null, $pagenumber = null)
    {
            $count = $messgelist = Allmessage::where('deleted','=','0')->where(function ($query) use ($userid,$usertoid) {
					$query->where(function ($query1) use ($userid,$usertoid) {
					$query1->where('userid','=',$userid)
								->where('usertoid','=',$usertoid);
			})->orWhere(function ($query2) use ($userid,$usertoid) {
					$query2->where('usertoid','=',$userid)
								->where('userid','=',$usertoid);
			});
			})->count();
			// reverse list
			if($perpage > 0):
				if($pagenumber > 1):
						$limit = $perpage;
						$offset = ($limit*$pagenumber)-$limit;
				else:
						$limit = $perpage;
						$offset = '0';
				endif;
			endif;

      $messgelist = Allmessage::where('deleted','=','0')->where(function ($query) use ($userid,$usertoid) {
 		  $query->where(function ($query1) use ($userid,$usertoid) {
 			$query1->where('userid','=',$userid)
          ->where('usertoid','=',$usertoid);
				})->orWhere(function ($query2) use ($userid,$usertoid) {
						$query2->where('usertoid','=',$userid)
									->where('userid','=',$usertoid);
				});
				})->orderBy('id','DESC')->orderby('isread','ASC');
			if($perpage > 0):
				$messgelist = $messgelist->offset($offset)->limit($limit);
			endif;
				$messgelist=$messgelist->get();

        if($messgelist):
            foreach($messgelist as $messl):
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
                $messl->unread = 111;
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

							#$cnt = UnreadUserMessage::where('userid', $userid)->where('messageid', $messageid)->count();
							UnreadUserMessage::where('messageid', $messl->id)->where('userid', $userid)->delete();

            endforeach;
        endif;
				$reversed = $messgelist->reverse();
				$reversed->all();
				return $reversed;
				$messgelist = array_reverse((array)$messgelist);
				$this->_pre($messgelist);
        return $messgelist;
    }
	public function scheduleSendMessage(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		elseif(trim($request->scheduleid) == ""):
            $response ['success'] = false;
			$response ['message'] = 'Schedule id can not blank.';
			return response ( $response, 200 );
		elseif(trim($request->message) == ""):
            $response ['success'] = false;
			$response ['message'] = 'Message can not blank.';
			return response ( $response, 200 );
		else:
		    $schedule_message1 = mobileapp::where('id',$request->userid)->count();
		    if($schedule_message1 > 0):

		        $schedule_message2 = ScheduleMessage::where('id',$request->scheduleid)->count();

		        if($schedule_message2 > 0):
		            $insertmessage = new Allmessage();
		            $insertmessage->userid = $request->userid;
		            $insertmessage->scheduleid = $request->scheduleid;
		            $insertmessage->message = $request->message;
								$insertmessage->created_at = date('Y-m-d H:i:s');
		            $insertmessage->save();
		            $response ['success'] = true;
			        $response ['message'] = 'Message send successfuly.';
			        return response ( $response, 200 );
		        else:
		            $response ['success'] = false;
			        $response ['message'] = 'Schedule id invalid.';
			        return response ( $response, 200 );
		        endif;
		    else:
		        $response ['success'] = false;
			    $response ['message'] = 'User id invalid.';
			    return response ( $response, 200 );
		    endif;
		endif;

	}
    public function inspirationalScheduleList(Request $request)
    {
        $userid = $request->userid;
        $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if(trim($userid) == "") :
			$response['success'] = false;
			$response['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		else:
			//// Include tagged user's message
		    $uservalidcheck = mobileapp::where('id',$userid)->count();
		    if($uservalidcheck < 1):
		        $response['success'] = false;
    			$response['message'] = 'User id invalid.';
			    return response($response, 200);
			else:
				$includeTagged = 1;
				$schedullist = $this->getUserMessage($userid, $includeTagged);
				$response['success'] = true;
				$response['message'] = 'Keymoment schedule list successfully get.';
				$response['data']->scheduleMessageList = $schedullist;
				return response($response, 200);
		    endif;
		endif;
    }
    public function deleteFavMessage(Request $request)
    {
    	$response['success'] = '';
		$response['message'] = '';
		$response['data'] = ( object ) array ();
		$message_type = $request->message_type;
		if (trim($request->messageid) == "") :
			$response['success'] = false;
			$response['message'] = 'Message id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->userid) == "") :
			$response['success'] = false;
			$response['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->message_type) == "") :
			$message_type = 'message';
			#$response['success'] = false;
			#$response['message'] = 'Message Type can not blank.';
			#return response ( $response, 200 );
		endif;
        $checkCountFavMessage = FavouriteMessages::where('messageid', $request->messageid)->where('userid', $request->userid)->count();
        if($checkCountFavMessage > 0):
            $deletedrecords = FavouriteMessages::where('messageid', $request->messageid)->where('userid', $request->userid)->delete();
            $response['success'] = true;
			$response['message'] = 'Favourite message successfuly deleted.';
			return response ( $response, 200 );
        else:
            $response['success'] = false;
			$response['message'] = 'This credentials records not found.';
			return response ( $response, 200 );
        endif;

    }
	public function FavMessage(Request $request)
	{
		$response['success'] = '';
		$response['message'] = '';
		$response['data'] = ( object ) array ();
		$message_type = $request->message_type;
		if (trim($request->messageid) == "") :
			$response['success'] = false;
			$response['message'] = 'Message id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->userid) == "") :
			$response['success'] = false;
			$response['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->message_type) == "") :
			$message_type = 'message';
			#$response ['success'] = false;
			#$response ['message'] = 'Message Type can not blank.';
			#return response ( $response, 200 );
		endif;
		if(trim($message_type) == 'schedule') {
			$allmessage = ScheduleMessage::where('id', $request->messageid)->count();
		} elseif(trim($message_type) == 'message') {
		  $allmessage = Allmessage::where('id', $request->messageid)->count();
		} else {
			$response['success'] = false;
			$response['message'] = 'Message type Invalid.';
			return response ( $response, 200 );
		}
		    if($allmessage > 0):
					$FavMessage = FavouriteMessages::where('message_type', $message_type)->where('messageid', $request->messageid)->where('userid', $request->userid)->first();

					if($FavMessage) {
						$response['success'] = true;
						$response['message'] = 'Your Message alreay in your favourite list.';
						return response ( $response, 200 );
					}
					$FavMessage = new FavouriteMessages();
					$FavMessage->userid = $request->userid;
					$FavMessage->messageid = $request->messageid;
					$FavMessage->message_type = $message_type;
					$FavMessage->save();

					$response['success'] = true;
					$response['message'] = 'Your Message successfully favourite.';
			    return response ( $response, 200 );
		    else:
		        $response['success'] = false;
			    $response['message'] = 'Message id invalid.';
			    return response ( $response, 200 );
		    endif;
	}
	 public function deleteInspirationalMessage(Request $request)
    {
    	$response['success'] = '';
		$response['message'] = '';
		$response['data'] = ( object ) array ();
		$message_type = $request->message_type;
		if (trim($request->messageid) == "") :
			$response['success'] = false;
			$response['message'] = 'Message id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->userid) == "") :
			$response['success'] = false;
			$response['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->message_type) == "") :
			$message_type = 'message';
			#$response ['success'] = false;
			#$response ['message'] = 'Message Type can not blank.';
			#return response ( $response, 200 );
		endif;
        $checkCountFavMessage = InspirationalMessages::where('messageid', $request->messageid)->where('userid', $request->userid)->count();
        if($checkCountFavMessage > 0):
            $deletedrecords = InspirationalMessages::where('messageid', $request->messageid)->where('userid', $request->userid)->delete();
            $response['success'] = true;
			$response['message'] = 'Inspirational message successfuly deleted.';
			return response ( $response, 200 );
        else:
            $response ['success'] = false;
			$response ['message'] = 'This credentials records not found.';
			return response ( $response, 200 );
        endif;

    }
    public function InspirationalMessage(Request $request)
    {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		$message_type = $request->message_type;
		if (trim($request->messageid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Message id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		elseif (trim($request->message_type) == "") :
			$message_type = 'message';
			#$response ['success'] = false;
			#$response ['message'] = 'Message Type can not blank.';
			#return response ( $response, 200 );
		endif;
		if(trim($message_type) == 'schedule') {
			$allmessage = ScheduleMessage::where('id', $request->messageid)->count();
		} elseif(trim($message_type) == 'message') {
		  $allmessage = Allmessage::where('id', $request->messageid)->count();
		} else {
			$response ['success'] = false;
			$response ['message'] = 'Message type Invalid.';
			return response ( $response, 200 );
		}
		    if($allmessage > 0):
					$InspMessages = InspirationalMessages::where('message_type', $message_type)->where('messageid', $request->messageid)->where('userid', $request->userid)->first();

					if($InspMessages)
					{
						$response ['success'] = true;
						$response ['message'] = 'Your Message alreay in your Inspirational list.';
						return response ( $response, 200 );
					}
					$InspMessage = new InspirationalMessages();
					$InspMessage->userid = $request->userid;
					$InspMessage->messageid = $request->messageid;
					$InspMessage->message_type = $message_type;
					$InspMessage->save();

					$response ['success'] = true;
					$response ['message'] = 'Your Message successfully inspirational.';
			    return response ( $response, 200 );
		    else:
		        $response ['success'] = false;
			    $response ['message'] = 'Message id invalid.';
			    return response ( $response, 200 );
		    endif;
	}
	public function getInspMessage(Request $request)
	{
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;
		$message_type = $request->message_type;
		if (trim($request->message_type) == "") :
			#$response ['success'] = false;
			#$response ['message'] = 'Message Type can not blank.';
			#return response ( $response, 200 );
			$message_type = 'message';
		endif;

		$arr__id = [];
		$cnt_insp = InspirationalMessages::select('messageid')->where('message_type', $message_type)->where('userid', $request->userid)->count();
		if($cnt_insp == 0) {
				$response ['success'] = true;
				$response ['message'] = 'Message list successfully get.';
				$response ['data']->inspirationalMessageList = '';
				return response ( $response, 200 );
		}
		$arr__inspMessageIds = InspirationalMessages::select('messageid')->where('message_type', $message_type)->where('userid', $request->userid)->get();
		foreach($arr__inspMessageIds as $msg) {
			$arr__id[] = $msg->messageid;
		}
		//print_r($arr__favMessageIds);die;
		$messgelist = Allmessage::select('id', 'userid', 'usertoid', 'scheduleid', 'message', 'messagetype')->whereIn('id', $arr__id)->orderby('isread','ASC')->orderBy('id','DESC')->get();
		if($messgelist):
			foreach($messgelist as $messl):
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
			endforeach;
		endif;
					#return $messgelist;
		$response ['success'] = true;
		$response ['message'] = 'Message list successfully get.';
		$response ['data']->inspirationalMessageList = $messgelist;
		return response ( $response, 200 );
	}
	public function getKeymomenttoList(Request $request)
	{
        $userid = $request->userid;
        $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if(trim($userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		else:
			//// Include tagged user's message
		    $uservalidcheck = mobileapp::where('id',$userid)->count();
		    if($uservalidcheck < 1):
		        $response ['success'] = false;
    			$response ['message'] = 'User id invalid.';
			    return response($response, 200);
			else:
			    $tageuseflag = '1';
				$schedullist = $this->getKeymomentUserMessage($userid,$tageuseflag, $request);
				$response ['success'] = true;
				$response ['message'] = 'Keymoment schedule list successfully get.';
				$response ['data']->scheduleMessageList = $schedullist;
				return response($response, 200);
		    endif;
		endif;
	}
	public function getKeymomentfromList(Request $request)
	{
        $userid = $request->userid;
        $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if(trim($userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		else:
			//// Include tagged user's message
		    $uservalidcheck = mobileapp::where('id',$userid)->count();
		    if($uservalidcheck < 1):
		        $response ['success'] = false;
    			$response ['message'] = 'User id invalid.';
			    return response($response, 200);
			else:

				$schedullist = $this->getKeymomentUserMessage($userid, null, $request);
				$response ['success'] = true;
				$response ['message'] = 'Keymoment schedule list successfully get.';
				$response ['data']->scheduleMessageList = $schedullist;
				return response($response, 200);
		    endif;
		endif;
	}
	public function _getSchedulebyKeymoment($userid = null, $key_id = null) {
		$msglist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date')->with ( array (
			'media_files' => function ($q) {
			$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
			}
			) )->with('keymoment')->with('tagged_users')
			->with(array('messages' => function ($query) {
				$query->where ( 'deleted', 0 )->latest()->limit(1);
				}))->where( 'userid', $userid )->where('key_id', $key_id)->where('archive', 0)->where('deleted','0')->orderBy('id','DESC')->get ();
		$msglist = $this->_format_schmessage($msglist, $userid);
		return $msglist;
	}
	public function getKeymomentScheduleList(Request $request)
	{
		$userid = $request->userid;
		$key_id = $request->key_id;
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if(trim($userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		elseif(trim($key_id) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Keymoment id can not blank.';
			return response ( $response, 200 );
		else:
			//// Include tagged user's message
		    $uservalidcheck = mobileapp::where('id',$userid)->count();
		    if($uservalidcheck < 1):
		      $response ['success'] = false;
    			$response ['message'] = 'User id invalid.';
			    return response($response, 200);
				elseif(Keymoment::where('id',$key_id)->count() < 1):
					$response ['success'] = false;
    			$response ['message'] = 'Keymoment id invalid.';
			    return response($response, 200);
			else:
				$schedullist = $this->_getSchedulebyKeymoment($userid, $key_id);
				$response ['success'] = true;
				$response ['message'] = 'Keymoment schedule list successfully get.';
				$response ['data']->scheduleMessageList = $schedullist;
				return response($response, 200);
		    endif;
		endif;
	}
	public function _getSchedulebyID($ids = array(), $userid, $request) {
		$msglist = ScheduleMessage::select (  'id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date')->with ( array (
			'media_files' => function ($q) {
			$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
			}
			) )->with('keymoment')->with('tagged_users')
			->with(array('messages' => function ($query) {
				$query->where ( 'deleted', 0 )->latest()->limit(1);
				}))->whereIn( 'id', $ids )->where('archive', 0)->where('deleted','0')->orderBy('id','DESC');//->get ();

		$perpage = $request->perpage;
		$pagenumber = $request->pagenumber;
		if($perpage > 0):
			if($pagenumber > 1):
					$limit = $perpage;
					$offset = ($limit*$pagenumber)-$limit;
			else:
					$limit = $perpage;
					$offset = '0';
			endif;
		endif;
		if($perpage > 0):
			$msglist = $msglist->offset($offset)->limit($limit);
		endif;
			$msglist = $msglist->get();

		$msglist = $this->_format_schmessage($msglist, $userid);
		return $msglist;
	}
	public function getFavMessage(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;
		$userid = $request->userid;
		$message_type = $request->message_type;
		if (trim($request->message_type) == "") :
			#$response ['success'] = false;
			#$response ['message'] = 'Message Type can not blank.';
			#return response ( $response, 200 );
			$message_type = 'message';
		endif;

		$arr__id = [];
		$cnt_fav = FavouriteMessages::select('messageid')->where('message_type', $message_type)->where('userid', $request->userid)->count();
		if($cnt_fav == 0) {
				$response ['success'] = true;
				$response ['message'] = 'Message list successfully get.';
				$response ['data']->favMessageList = '';
				return response ( $response, 200 );
		}
		$arr__favMessageIds = FavouriteMessages::select('messageid')->where('message_type', $message_type)->where('userid', $request->userid)->get();
		foreach($arr__favMessageIds as $msg) {
			$arr__id[] = $msg->messageid;
		}
		$messgelist = Allmessage::select('id', 'userid', 'usertoid', 'scheduleid')->whereIn('id', $arr__id)->orderby('isread','ASC')->orderBy('id','DESC')->get();
		$arr_schdule_id = [];
		if($messgelist){
			foreach($messgelist as $messl){
				$arr_schdule_id[] = $messl->scheduleid;
			}
		}
		if(empty($arr_schdule_id)) {
			$response ['success'] = true;
			$response ['message'] = 'Message list successfully get.';
			$response ['data']->favMessageList = '';
			return response ( $response, 200 );
		}
		$messgelist = $this->_getSchedulebyID($arr_schdule_id, $userid, $request);
		$response ['success'] = true;
		$response ['message'] = 'Message list successfully get.';
		$response ['data']->favMessageList = $messgelist;
		return response ( $response, 200 );

		//print_r($arr__favMessageIds);die;
		$messgelist = Allmessage::select('id', 'userid', 'usertoid', 'scheduleid', 'message', 'messagetype')->whereIn('id', $arr__id)->orderby('isread','ASC')->orderBy('id','DESC')->get();
		if($messgelist):
			foreach($messgelist as $messl):
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
			endforeach;
		endif;
					#return $messgelist;
		$response ['success'] = true;
		$response ['message'] = 'Message list successfully get.';
		$response ['data']->favMessageList = $messgelist;
		return response ( $response, 200 );
	}
	private function _setNotification($id, $type = 'message', $userid = null, $key_id = null)
	{
		$msg = null;
		switch($type) {
			case 'message':
				// Set Notification
				$byuserDetail = mobileapp::where('id',$userid)->first();
				$byname = $byuserDetail->full_name;
				$title = 'New message from '. $byname;
				$msg = ($msg) ? $msg : 'New message';
				$this->setNotification($id, $msg, 'message', $title, $key_id);
				// End notification
			break;
			case 'schedule':
				$schedule = $id; // Schedule data

				$key = Keymoment::find($schedule->key_id);
				$schedule_title = $key->title;
				$tagged_users = MessageTaggedUser::where('messageid', $schedule->id)->get();
				$msg = ($msg) ? $msg : 'New Schedule message';
				if(count($tagged_users) > 0) {
					foreach($tagged_users as $user) {

						if($userid != $user->userid) {
							$noti_userid = $user->userid;
						} else {
							$noti_userid = $schedule->userid;
						}
						$this->setNotification($noti_userid, $msg, 'schedule', $schedule_title, $schedule->id);
					}
				}
				break;
			case 'new_schedule': //'key moment name user tagged another user '
				$schedule = $id; // Schedule data

				$byuser = $schedule->userid;
				$byuserDetail = mobileapp::where('id',$byuser)->first();
				$byname = $byuserDetail->full_name;
				$key = Keymoment::find($schedule->key_id);
				$schedule_title = $key->title;

				$tagged_users = MessageTaggedUser::where('messageid', $schedule->id)->get();
				$msg = ($msg) ? $msg : 'New Keymoment Schedule';
				$msg = "You have tagged by {$byname}";
				if(count($tagged_users) > 0) {
					foreach($tagged_users as $user) {
						$this->setNotification($user->userid, $msg, 'schedule', $schedule_title, $schedule->id);
					}
				}
				break;
		}

	}

	public function updateUnreadScheduleMessage(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;
		if (trim($request->scheduleid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Schedule id can not blank.';
			return response ( $response, 200 );
		endif;
		$scheduleid = $request->scheduleid;
		$userid = $request->userid;

		UnreadUserMessage::where('scheduleid', $scheduleid)->where('userid', $userid)->delete();

		$response ['success'] = true;
		$response ['message'] = 'Schedule message read status updated successfully.';

		return response ( $response, 200 );

	}

	public function getScheduleDetail(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;

		if (trim($request->scheduleid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Schedule id can not blank.';
			return response ( $response, 200 );
		endif;
		$scheduleid = $request->scheduleid;
		$userid = $request->userid;

		$msg = 	ScheduleMessage::select('schedule_messages.id', 'userid', 'key_id', 'message', 'read_status', 'schedule_date')
		->with('keymoment')->with ( array (
			'media_files' => function ($q) {
			$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
			}
			) )->with('tagged_users')
			->where('id',$scheduleid)->where('schedule_messages.deleted','0')->orderBy('id','DESC')->get ();
		// ->where('userid', $userid) ; Remove userid

		if($msg) {
			foreach($msg as $msgl):

				$dt = $msgl->schedule_date;
				$msgl->schedule_date = date('d-m-Y', $dt);
				$msgl->schedule_time = date('h:i A', $dt);
				$msgl->userDetails = $this->getUserDetails($msgl->userid, true);
					$tageuser = array();
					if($msgl->tagged_users):
							$tagusers = $msgl->tagged_users;
							unset($msgl->tagged_users);
							foreach($tagusers as $taged):
											array_push($tageuser,$this->getUserDetails($taged->userid, true));
							endforeach;
							$msgl->tagged_users = $tageuser;
					endif;
			endforeach;
		}
		$response ['success'] = true;
		$response ['message'] = 'Schedule detail successfully got.';
		if(isset($msg[0])) {
			$response ['data']->scheduleDetail =  $msg[0] ;
		}
		else {
			$response ['success'] = false;
			$response ['message'] = 'No record found';
		}
		return response ( $response, 200 );
	}

	public function _updateUserSpace($filesize, $userid) {
		$user = mobileapp::find ( $userid );
		$user->total_size = $user->total_size - $filesize;
		$user->save();
	}

	public function _checkUserSpace($userid) {
		$user = mobileapp::find ( $userid );
		$remain_space =  $user->available_size - $user->total_size;
		return $user->total_size;
	}

}
?>