<?php
namespace App\Http\Controllers\api;

use App\InviteVideo;
use App\mobileapp;
use Illuminate\Http\Request;
use App\ScheduleMessage;
use File, DB;

class VideoController extends CommonController
{

	/**
	 *
	 * @param unknown $id
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function inviteUploadVideo(Request $request)
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
		$file = $request->file('video_file');

    if(!$file) {
			$response ['success'] = false;
			$response ['message'] = 'Video file can not blank';
      return response($response, 200);
		}

		if(!empty($file))
		{
			$userid = $request->userid;
			$usercheck = mobileapp::where('id',$userid)->count();
			if($usercheck > 0) {
				$video_file = new InviteVideo();
				$image = $file;
				$org_name = $file->getClientOriginalName();
				//$filetype = $this->mime2ext($file->getClientmimeType());
				//echo $filetype;echo '<br>';exit($file->getClientmimeType());
				#if ($filetype == 'video') {
					$fileName = time().'.'.$file->getClientOriginalExtension();
					$destinationPath = $this->current_volume_path_upload . '/videos/';

					$mv = $file->move($destinationPath,  $fileName);

					$video_file->userid = $request->userid;
					$video_file->filename = $fileName;

					$video_file->save();
					$url  = url().'/' . $destinationPath . $fileName;
					$response ['success'] = true;
					$response ['message'] = 'Video uploaded successfully.';
					$response ['data']->videoUrl = $url;
					return response ( $response, 200 );
				/*
				} else {
					$response ['success'] = false;
					$response ['message'] = 'Only video file allow.';
					return response ( $response, 200 );
				}*/
			} else {
				$response['success'] = false;
				$response['message'] = 'User id invalid.';
				return response ( $response, 200 );
			}
		}
		$response ['success'] = false;
		$response ['message'] = 'Try again.';

		return response ( $response, 200 );
	}

}


?>
