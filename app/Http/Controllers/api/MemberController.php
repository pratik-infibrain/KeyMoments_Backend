<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\User_setting;
use App\mobileapp;
use App\Childrendetail;
use App\userexicuter;
use File, DB;
use App\ScheduleMessage;
use App\Keymoment;
use App\MessageTaggedUser;
use App\FavouriteMessages;
use App\Allmessage;
use App\InspirationalMessages;

class MemberController extends CommonController
{
	public function countpages($path)
	{
		$pdf = file_get_contents ( $path );
		$number = preg_match_all ( "/\/Page\W/", $pdf, $dummy );
		return $number;
	}
	public function changepassword(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		/*
		 * if(trim($request->oldpassword) ==""):
		 * $response['success'] = false;
		 * $response['message'] = 'Old password can not be blank.';
		 * return response($response, 200);
		 * endif;
		 */
		if (trim ( $request->password ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Password can not be blank.';
			return response ( $response, 200 );

        endif;
		/*
		 * if(trim($request->retypenewpassword)==""):
		 * $response['success'] = false;
		 * $response['message'] = 'Retype new password can not be blank.';
		 * return response($response, 200);
		 * endif;
		 * if($request->newpassword!=$request->retypenewpassword):
		 * $response['success'] = false;
		 * $response['message'] = 'New password does not match with retype new password.';
		 * return response($response, 200);
		 * endif;
		 */
		if (trim ( $request->userid ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not be blank.';
			return response ( $response, 200 );

        endif;
		$memberDetails = mobileapp::with ( 'childrens' )->with ( 'executors' )->where ( 'deleted', 0 )->where ( 'id', $request->userid )->first ();

		if ($memberDetails->status == '0') :
			$response ['success'] = false;
			$response ['message'] = 'Your account has been inactive please contact to admin.';
			return response ( $response, 200 );
		 elseif ($memberDetails->isDisabled == 'Yes') :
			$response ['success'] = false;
			$response ['message'] = 'Your account has been disabled please contact to admin.';
			return response ( $response, 200 );
		endif;
		if ($memberDetails) :
			if ($memberDetails->password != "") :
				// if(Hash::check($request->oldpassword,$memberDetails->password)):
				if ($request->oldpassword == $memberDetails->password) :

					$users = mobileapp::find ( $memberDetails->id );
					// $users->password = Hash::make($request->newpassword);
					$users->password = $request->password;
					$users->save ();
					$response ['success'] = true;
					$response ['message'] = 'Your password changed successfully.';
					// $memberDetails->profileimageurl = url().'/public/uploads/profileimage/'.$memberDetails->profileimage;
					unset ( $memberDetails ['Otp'] );
					unset ( $memberDetails ['otpdatetime'] );
					unset ( $memberDetails ['otpstatus'] );
					unset ( $memberDetails ['loginstatus'] );
					unset ( $memberDetails ['isDisabled'] );
					unset ( $memberDetails ['status'] );
					unset ( $memberDetails ['deleted'] );
					unset ( $memberDetails ['updated_at'] );
					unset ( $memberDetails ['created_at'] );
					// $response['data']->memberDetails = $memberDetails;
					return response ( $response, 200 );
				 else :
					$response ['success'] = false;
					$response ['message'] = 'Your old password does not match with your password.';
					return response ( $response, 200 );
				endif;
			 else :
				$users = mobileapp::find ( $memberDetails->id );
				// $users->password = Hash::make($request->newpassword);
				$users->password = $request->newpassword;
				$users->save ();
				unset ( $memberDetails ['Otp'] );
				unset ( $memberDetails ['otpdatetime'] );
				unset ( $memberDetails ['otpstatus'] );
				unset ( $memberDetails ['loginstatus'] );
				unset ( $memberDetails ['isDisabled'] );
				unset ( $memberDetails ['status'] );
				unset ( $memberDetails ['deleted'] );
				unset ( $memberDetails ['updated_at'] );
				unset ( $memberDetails ['created_at'] );
				$response ['success'] = true;
				$response ['message'] = 'Your password changed successfully.';
				// $memberDetails->profileimageurl = url().'/public/uploads/profileimage/'.$memberDetails->profileimage;
				// $response['data']->memberDetails = $memberDetails;
				return response ( $response, 200 );
			endif;
		 else :
			$response ['success'] = false;
			$response ['message'] = 'User id invalid.';
			return response ( $response, 200 );
		endif;
	}
	public function forgotpassword(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		$emailphone = $request->emailphone;

		if (trim ( $emailphone ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Email or Phone can not be blank.';
			return response ( $response, 200 );
		 else :
			$checkemail = $this->checkemail ( $emailphone );
			if (! $checkemail) :
				$memberDetails = mobileapp::with ( 'childrens' )->with ( 'executors' )->where ( 'deleted', 0 )->where ( 'mobile_number', $emailphone )->first ();
				if ($memberDetails) :
					$otp = rand ( 1000, 9999 );
					$otpdatetime = date ( 'Y-m-d H:i:s', strtotime ( "+2 minutes", strtotime ( date ( 'Y-m-d H:i:s' ) ) ) );
					$users = mobileapp::find ( $memberDetails->id );
					$users->Otp = $otp;
					$users->otpdatetime = $otpdatetime;
					$users->save ();

					$admin_user = $this->getadminuser ( '1' );
					$site_email = $admin_user->email;
					$site_name = $admin_user->name;
					$headers = 'Content-type: text/html;<br>From: ' . $site_email;

					$message = 'hello ' . $memberDetails->full_name . ', <br>Your password reset otp : ' . $otp . '. <p>Otp expried in 2 mins.</p>';
					mail ( $memberDetails->email, 'Password reset otp', $message, $headers );

					$response ['success'] = true;
					$response ['message'] = 'Password otp send successfully please check your registed email.';

					unset ( $memberDetails ['Otp'] );
					unset ( $memberDetails ['otpdatetime'] );
					unset ( $memberDetails ['otpstatus'] );
					unset ( $memberDetails ['loginstatus'] );
					unset ( $memberDetails ['isDisabled'] );
					unset ( $memberDetails ['status'] );
					unset ( $memberDetails ['deleted'] );
					unset ( $memberDetails ['updated_at'] );
					unset ( $memberDetails ['created_at'] );
					$memberDetails->otp = $otp;
					$response ['data']->memberDetails = $memberDetails;
					return response ( $response, 200 );
				 else :
					$response ['success'] = false;
					$response ['message'] = 'Invalid email or phone number please enter correct details.';
					return response ( $response, 200 );
				endif;
			 else :
				$memberDetails = mobileapp::with ( 'childrens' )->with ( 'executors' )->where ( 'deleted', 0 )->where ( 'email', $emailphone )->first ();

				if ($memberDetails) :
					$otp = rand ( 1000, 9999 );
					$otpdatetime = date ( 'Y-m-d H:i:s', strtotime ( "+2 minutes", strtotime ( date ( 'Y-m-d H:i:s' ) ) ) );
					$users = mobileapp::find ( $memberDetails->id );
					$users->Otp = $otp;
					$users->otpdatetime = $otpdatetime;
					$users->save ();

					$admin_user = $this->getadminuser ( '1' );
					$site_email = $admin_user->email;
					$site_name = $admin_user->name;
					$headers = 'Content-type: text/html;<br>From: ' . $site_email;

					$message = 'hello ' . $memberDetails->full_name . ', <br>Your password reset otp : ' . $otp . '. <p>Otp expried in 2 mins.</p>';
					mail ( $memberDetails->email, 'Password reset otp', $message, $headers );

					$response ['success'] = true;
					$response ['message'] = 'Password otp send successfully please check your registed email.';
					unset ( $memberDetails ['Otp'] );
					unset ( $memberDetails ['otpdatetime'] );
					unset ( $memberDetails ['otpstatus'] );
					unset ( $memberDetails ['loginstatus'] );
					unset ( $memberDetails ['isDisabled'] );
					unset ( $memberDetails ['status'] );
					unset ( $memberDetails ['deleted'] );
					unset ( $memberDetails ['updated_at'] );
					unset ( $memberDetails ['created_at'] );
					$memberDetails->otp = $otp;
					$response ['data']->memberDetails = $memberDetails;
					return response ( $response, 200 );


				else :
					$response ['success'] = false;
					$response ['message'] = 'Invalid email please enter correct details.';
					return response ( $response, 200 );
				endif;
			endif;


		endif;
	}
	public function varifiedotp(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if ($request->userid == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not be blank.';
			return response ( $response, 200 );
        endif;
		if ($request->otp == "") :
			$response ['success'] = false;
			$response ['message'] = 'Otp can not be blank.';
			return response ( $response, 200 );
        endif;
		$memberDetails = mobileapp::with ( 'childrens' )->with ( 'executors' )->where ( 'deleted', 0 )->where ( 'deleted', 0 )->where ( 'id', $request->userid )->first ();
		if ($memberDetails) :
			if ($memberDetails->otpstatus == '1') :
				$response ['success'] = false;
				$response ['message'] = 'Your Otp already verified.';
				return response ( $response, 200 );
			 else :
				if ($memberDetails->Otp == trim ( $request->otp )) :
					$add2minsdate = strtotime ( date ( "Y-m-d H:i:s" ) );
					$otdate = strtotime ( $memberDetails->otpdatetime );
					if ($otdate < $add2minsdate) :
						$response ['success'] = false;
						$response ['message'] = 'Your Otp Expired.';
						return response ( $response, 200 );
					 else :
						unset ( $memberDetails ['Otp'] );
						unset ( $memberDetails ['otpdatetime'] );
						unset ( $memberDetails ['otpstatus'] );
						unset ( $memberDetails ['loginstatus'] );
						unset ( $memberDetails ['isDisabled'] );
						unset ( $memberDetails ['status'] );
						unset ( $memberDetails ['deleted'] );
						unset ( $memberDetails ['updated_at'] );
						unset ( $memberDetails ['created_at'] );
						$users = mobileapp::find ( $memberDetails->id );
						$users->otpstatus = '1';
						$users->save ();
						$response ['success'] = true;
						$response ['message'] = 'Your otp varified successfully.';
						$response ['data']->memberDetails = $memberDetails;
						return response ( $response, 200 );
					endif;
				 else :
					$response ['success'] = false;
					$response ['message'] = 'Invalid OTP.';
					return response ( $response, 200 );
				endif;
			endif;
		 else :
			$response ['success'] = false;
			$response ['message'] = 'User id invalid.';
			return response ( $response, 200 );
		endif;
	}
	public function resetpassword(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if ($request->userid == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not be blank.';
			return response ( $response, 200 );
        endif;
		if (trim ( $request->password ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Password can not be blank.';
			return response ( $response, 200 );
		    endif;
		$memberDetails = mobileapp::with ( 'childrens' )->with ( 'executors' )->where ( 'deleted', 0 )->where ( 'id', $request->userid )->first ();
		if ($memberDetails) :
			if ($memberDetails->otpstatus == '1') :
				$users = mobileapp::find ( $memberDetails->id );
				// $users->password = Hash::make($request->password);
				$users->password = $request->password;
				$users->otpstatus = '0';
				$users->Otp = null;
				$users->otpdatetime = null;
				$users->save ();

				unset ( $memberDetails ['Otp'] );
				unset ( $memberDetails ['otpdatetime'] );
				unset ( $memberDetails ['otpstatus'] );
				unset ( $memberDetails ['loginstatus'] );
				unset ( $memberDetails ['isDisabled'] );
				unset ( $memberDetails ['status'] );
				unset ( $memberDetails ['deleted'] );
				unset ( $memberDetails ['updated_at'] );
				unset ( $memberDetails ['created_at'] );

				$response ['success'] = true;
				$response ['message'] = 'Your password reset successfully.';
				$memberDetails->password = $request->password;
				$response ['data']->memberDetails = $memberDetails;
				return response ( $response, 200 );
			 else :
				$response ['success'] = false;
				$response ['message'] = 'Please varified your otp first.';
				return response ( $response, 200 );
			endif;
		 else :
			$response ['success'] = false;
			$response ['message'] = 'User id invalid.';
			return response ( $response, 200 );
		endif;
	}

	/**
	 * delete unwanted key value of members details
	 *
	 * @param unknown $memberDetails
	 * @return unknown
	 */
	private function unsetMemberDetails($memberDetails) {
		unset ( $memberDetails ['Otp'] );
		unset ( $memberDetails ['otpdatetime'] );
		unset ( $memberDetails ['otpstatus'] );
		unset ( $memberDetails ['loginstatus'] );
		unset ( $memberDetails ['isDisabled'] );
		unset ( $memberDetails ['status'] );
		unset ( $memberDetails ['deleted'] );
		unset ( $memberDetails ['updated_at'] );
		unset ( $memberDetails ['created_at'] );
		if($memberDetails ['profile_photo'] !=""):
		    $memberDetails ['profile_photo'] = url() . '/' . $memberDetails ['profile_photo'];
		else:
		    $memberDetails ['profile_photo'] = null;
		endif;

		return $memberDetails;
	}

	/**
	 * Update Profile
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	public function updateProfile(Request $request, $id) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->email ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Email address can not blank.';
			return response ( $response, 200 );
		 else :
			$checkemail = $this->checkemail ( $request->email );
			if (! $checkemail) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid email address.';
				return response ( $response, 200 );

    	endif;
		endif;

		if (trim ( $request->firstname ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Firstname can not blank.';
			return response ( $response, 200 );

		endif;

		if (trim ( $request->lastname ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Lastname can not blank.';
			return response ( $response, 200 );

   	endif;

		if (trim ( $request->mobile_number ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Mobile number can not blank.';
			return response ( $response, 200 );

   	endif;

		if (trim ( $request->short_detail ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Short detail can not blank.';
			return response ( $response, 200 );

   	endif;

		$memberDetails = mobileapp::where ( 'deleted', 0 )->where ( 'email', $request->email )->where ( 'id', '!=', $id )->first ();

		if (sizeof ( $memberDetails ) < 1) :

			$users = mobileapp::find ( $id );
			$users->email = $request->email;
			$users->full_name = $request->firstname . ' ' . $request->lastname;
			$users->firstname = $request->firstname;
			$users->lastname = $request->lastname;
			$users->mobile_number = $request->mobile_number;
			$users->short_detail = $request->short_detail;

			// Profile image
			if ($request->hasFile ( 'profile_photo' )) {
				$image = $request->file ( 'profile_photo' );
				$imageName = $id . '.' . $image->getClientOriginalExtension ();
				// $imgname = $image->getClientOriginalName();
				$destinationPath = $this->profile_image_path;
				$image->move ( $destinationPath, $imageName );
				$docuemntfileurl = url ( '/' ) . '/' . $this->profile_image_path . $imageName;
				$users->profile_photo = $destinationPath . $imageName;
			}

			$users->save ();

			//$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ) )->with ( $this->__withQRY ( 'executors' ) )->where ( 'deleted', 0 )->where ( 'id', $id )->first ();
			$response ['success'] = true;
			$response ['message'] = 'Profile updated successfully.';
			//$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
			//$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );
			$memberDetails1 = $this->getUserDetails($id);
			$response ['data']->memberDetails = $memberDetails1;
		 else :
			$response ['success'] = false;
			$response ['message'] = 'Your email already exits please login.';
		endif;
		return response ( $response, 200 );
	}

	/**
	 * Update personal details
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	public function updatePersonal(Request $request, $id) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim ( $request->gender ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Gender can not blank.';
			return response ( $response, 200 );
		 else :
			$genderarray = array (
					'male',
					'female',
					'other'
			);
			if (! in_array ( strtolower ( $request->gender ), $genderarray )) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid gender.';
				return response ( $response, 200 );

		endif;
		endif;
		if (trim ( $request->dateofbirth ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Date of birth can not blank.';
			return response ( $response, 200 );

		endif;
		if (trim ( $request->marital_status ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Marital status can not blank.';
			return response ( $response, 200 );
		 else :
			$maritalstatusarray = array (
					'Married',
					'Single',
					'Divorced'
			);
			if (! in_array ( ucfirst ( $request->marital_status ), $maritalstatusarray )) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid marital status.';
				return response ( $response, 200 );

		endif;
		endif;

		// $dateofbirth = str_replace("/", "-", $request->dateofbirth);
		$dateofbirthtemp = explode ( '/', $request->dateofbirth );
		$dateofbirth = $dateofbirthtemp [1] . '-' . $dateofbirthtemp [0] . '-' . $dateofbirthtemp [2];
		$users = mobileapp::find ( $id );
		$users->gender = strtolower ( $request->gender );
		$users->dateofbirth = date ( 'Y-m-d', strtotime ( $dateofbirth ) );
		$users->marital_status = ucfirst ( $request->marital_status );
		$users->save ();

		#$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ) )->with ( $this->__withQRY ( 'executors' ) )->where ( 'deleted', 0 )->where ( 'id', $id )->first ();
		$response ['success'] = true;
		$response ['message'] = 'Personal details updated successfully.';
		#$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
		#$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );
		$memberDetails1 = $this->getUserDetails($id);
		$response ['data']->memberDetails = $memberDetails1;

		return response ( $response, 200 );
	}

	/**
	 * Update children
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	public function updateChild(Request $request, $id) {
		$child = Childrendetail::find ( $id );
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->name ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Children name can not blank.';
			return response ( $response, 200 );

		endif;

		$child->name = $request->name;
		if (trim ( $request->email ) != "") :
			$checkemail = $this->checkemail ( $request->email );

			if (! $checkemail) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid email address.';
				return response ( $response, 200 );
			endif;

			$child->email = $request->email;

		endif;

		if (trim ( $request->phone ) != "") :
			$child->phone = $request->phone;
		endif;

		$child->save ();
		#$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ) )->with ( $this->__withQRY ( 'executors' ) )->where ( 'deleted', 0 )->where ( 'id', $child->userid )->first ();
		$response ['success'] = true;
		$response ['message'] = 'Children details updated successfully.';
		#$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
		#$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );

		$memberDetails1 = $this->getUserDetails($id);
		$response ['data']->memberDetails = $memberDetails1;

		return response ( $response, 200 );
	}
	public function addChild(Request $request, $id) {
		$child = new Childrendetail ();
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->name ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Children name can not blank.';
			return response ( $response, 200 );
		endif;

		$child->name = $request->name;
		if (trim ( $request->email ) != "") :
			$checkemail = $this->checkemail ( $request->email );

			if (! $checkemail) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid email address.';
				return response ( $response, 200 );
		endif;

			$child->email = $request->email;

		endif;

		if (trim ( $request->phone ) != "") :
			$child->phone = $request->phone;

		endif;

		$child->userid = $id;
		$child->save ();
		#$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ) )->with ( $this->__withQRY ( 'executors' ) )->where ( 'deleted', 0 )->where ( 'id', $child->userid )->first ();
		$response ['success'] = true;
		$response ['message'] = 'Children details added successfully.';
		#$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
		#$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );
		$memberDetails1 = $this->getUserDetails($id);
		$response ['data']->memberDetails = $memberDetails1;

		return response ( $response, 200 );
	}
	/**
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	public function addExecutor(Request $request, $id) {
		$executor = new userexicuter ();
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->name ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Name can not blank.';
			return response ( $response, 200 );
		endif;

		$executor->name = $request->name;
		if (trim ( $request->email ) != "") :
			$checkemail = $this->checkemail ( $request->email );
			if (! $checkemail) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid email address.';
				return response ( $response, 200 );
			endif;
			$executor->email = $request->email;
		endif;

		if (trim ( $request->phone ) != "") :
			$executor->phone = $request->phone;
		endif;

		$executor->user_id = $id;

		$executor->save ();
		#$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ) )->with ( $this->__withQRY ( 'executors' ) )->where ( 'deleted', 0 )->where ( 'id', $executor->user_id )->first ();
		$response ['success'] = true;
		$response ['message'] = 'Executor details added successfully.';
		#$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
		#$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );
		$memberDetails1 = $this->getUserDetails($id);
		$response ['data']->memberDetails = $memberDetails1;

		return response ( $response, 200 );
	}

	/**
	 * Update Executor
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	public function updateExecutor(Request $request, $id) {
		$executor = userexicuter::find ( $id );
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->name ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Name can not blank.';
			return response ( $response, 200 );
		endif;

		$executor->name = $request->name;
		if (trim ( $request->email ) != "") :
			$checkemail = $this->checkemail ( $request->email );

			if (! $checkemail) :
				$response ['success'] = false;
				$response ['message'] = 'Invalid email address.';
				return response ( $response, 200 );

			endif;
			$executor->email = $request->email;
		endif;

		if (trim ( $request->phone ) != "") :
			$executor->phone = $request->phone;
		endif;

		$executor->save ();
		#$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ) )->with ( $this->__withQRY ( 'executors' ) )->where ( 'deleted', 0 )->where ( 'id', $executor->user_id )->first ();
		$response ['success'] = true;
		$response ['message'] = 'Executor details updated successfully.';
		#$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
		#$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );
		$memberDetails1 = $this->getUserDetails($id);
		$response ['data']->memberDetails = $memberDetails1;

		return response ( $response, 200 );
	}

	/**
	 * Update Education
	 *
	 * @param Request $request
	 * @param unknown $id
	 */
	public function updateEducation(Request $request, $id) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->education ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Education can not blank.';
			return response ( $response, 200 );

		endif;

		if (trim ( $request->military_status ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Military status can not blank.';
			return response ( $response, 200 );
		endif;

		if (trim ( $request->employment ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Employment can not blank.';
			return response ( $response, 200 );
		endif;

		// $dateofbirth = str_replace("/", "-", $request->dateofbirth);
		$users = mobileapp::find ( $id );
		$users->education = $request->education;
		$users->military_status = $request->military_status;
		$users->employment = $request->employment;

		$users->save ();
		// New response code

		#$data = $this->getUserDetails($id);

		// End new response code

		#$memberDetails1 = mobileapp::with ( $this->__withQRY ( 'childrens' ))->with ( $this->__withQRY ( 'executors' ))->where ( 'deleted', 0 )->where ( 'id', $id )->first ();

		$response ['success'] = true;
		$response ['message'] = 'Education updated successfully.';
		#$loginstatustext = $this->loginstatus ( $memberDetails1->loginstatus );
		#$memberDetails1 = $this->unsetMemberDetails ( $memberDetails1 );
		$memberDetails1 = $this->getUserDetails($id);
		$response ['data']->memberDetails = $memberDetails1;
		return response ( $response, 200 );
	}
	function __withQRY($type) {
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

	/**
	 * Delete user
	 *
	 * @param Request $request
	 */
	public function deleteUser(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );

		endif;

		// Delete children
		DB::table ( 'childrendetails' )->where ( 'userid', $request->id )->update ( array (
				'deleted' => 1
		) );

		// Delete Exicuters
		DB::table ( 'userexicuters' )->where ( 'user_id', $request->id )->delete ();

		// Delete Package
		DB::table ( 'userpackages' )->where ( 'userid', $request->id )->update ( array (
				'deleted' => 1
		) );

		// Delete User
		$user = mobileapp::find ( $request->id );
		$user->deleted = 1;
		$user->save ();

		$response ['success'] = true;
		$response ['message'] = 'User deleted successfully.';

		return response ( $response, 200 );
	}

	/**
	 * Delete Chile
	 *
	 * @param Request $request
	 */
	public function deleteChild(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Child id can not blank.';
			return response ( $response, 200 );

		endif;

		// Delete children
		DB::table ( 'childrendetails' )->where ( 'id', $request->id )->update ( array (
				'deleted' => 1
		) );


		$response ['success'] = true;
		$response ['message'] = 'Child deleted successfully.';

		return response ( $response, 200 );
	}

	/**
	 * Delete Exicuter
	 *
	 * @param Request $request
	 */
	public function deleteExecutor(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Exicuter id can not blank.';
			return response ( $response, 200 );
		endif;

		// Delete Exicuters
		DB::table ( 'userexicuters' )->where ( 'id', $request->id )->delete ();

		$response ['success'] = true;
		$response ['message'] = 'Exicuter deleted successfully.';

		return response ( $response, 200 );
	}

	/**
	 * Delete Notification
	 *
	 * @param Request $request
	 */
	public function deleteNotification(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Notification id can not blank.';
			return response ( $response, 200 );
		endif;

		// Delete Notification
		DB::table ( 'notification_alert' )->where ( 'id', $request->id )->delete ();

		$response ['success'] = true;
		$response ['message'] = 'Notification deleted successfully.';

		return response ( $response, 200 );
	}

	public function emailNotification(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;

		$user_id = $request->id;
		$arr__data = [];
		#$arr__data['email_alert'] = 0;
		$msg_type = '';

		if(trim($request->email) != '' ) {
			$arr__data['email_alert'] = $request->email;
			$msg_type = 'Email alert';
		}

		#$arr__data['push_notification'] = 0;
		if(trim($request->push_notification) != '') {
			$arr__data['push_notification'] = $request->push_notification;
			$msg_type = 'Push notification';
		}
		if ($msg_type == '') {
			$response ['success'] = false;
			$response ['message'] = 'Push notification or Email alert can not blank.';
			return response ( $response, 200 );
		}
		$user_setting = User_setting::where('user_id', $user_id)->first();
		if(sizeof($user_setting) > 0) {
			$data = $user_setting->data;
			$data = unserialize($data);
			$data = $arr__data + $data;
			$data = serialize($data);
			$user_setting->data = $data;
		} else {
			$user_setting = new User_setting();
			$user_setting->data = serialize($arr__data);
			$user_setting->user_id = $user_id;
		}
		 $user_setting->save();
		$response ['success'] = true;
		$response ['message'] = $msg_type .' saved successfully.';

		return response ( $response, 200 );
	}
	public function getUsetEmailNotification(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;

		$user_id = $request->id;
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
		} else {
			$response ['success'] = false;
		}
		$current_status = array('notification_status' => $notification_status,'email_status' => $email_status);
		$response ['data'] = ( object ) $current_status;
		$response ['success'] = true;
		$response ['message'] = 'successfully.';
		return response ( $response, 200 );
	}

	public function userlist(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if(empty($request->phones)):
		    $response ['success'] = false;
	    	$response ['message'] = 'Phone number can not be blank.';
		else:
	        $memberList = mobileapp::whereIn('mobile_number',$request->phones)->where('deleted','=','0')->where('status','=','1')->where('isDisabled','=','No')->get();
    		if($memberList):
    		    foreach($memberList as $members):
    		        $this->unsetMemberDetails($members);
    		    endforeach;
    		endif;
    		$response ['success'] = true;
    		$response ['message'] = 'User successfully get.';
    		$response ['data']->memberList = $memberList;
		endif;
		return response ( $response, 200 );
	}

	public function getTaggeduids($userid, $phone) {
		$uids = DB::table('schedule_messages as s')
			->leftjoin('message_tagged_users as tu', 'tu.messageid', '=', 's.id')
			->leftjoin('mobileapps as u', 'u.id', '=', 's.userid')
			->select('s.userid', 's.id')
			->where('tu.userid', $userid)
			->whereNotIn('u.mobile_number', $phone)
			->get();//*/
			$tagged_uid = [];
			foreach($uids as $row_uid)  {
				$tagged_uid[] = $row_uid->id;
			}
			//$this->_pre($tagged_uid);
			$tagged_uid = array_unique($tagged_uid);
			//$this->_pre($tagged_uid);
			return $tagged_uid;
	}



	public function InspirationalList(Request $request)
	{
	  $response ['success'] = '';
	  $response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if(trim($request->userid) == ''):
			$response ['success'] = false;
	    $response ['message'] = 'User Id can not be blank.';
		elseif(empty($request->phones)):
			$response ['success'] = false;
			$response ['message'] = 'Phone number can not be blank.';
		else:
			$userid = $request->userid;
			//*
			$tagged_uid = [];//$this->getTaggeduids($userid, $request->phones);

			#if(empty($tagged_uid)) {
		#		$memberList = mobileapp::whereNotIn('mobile_number',$request->phones)->where('deleted','=','0')->where('status','=','1')->where('isDisabled','=','No')->get();
		#	}
		#	else {
				$memberList = mobileapp::whereNotIn('mobile_number',$request->phones)->whereIn('id', $tagged_uid)->where('deleted','=','0')->where('status','=','1')->where('isDisabled','=','No')->get();
		#	}
        #$memberidaaray = array();
    	#	if($memberList):
    	#	    foreach($memberList as $members):
    	#	        array_push($memberidaaray,$members->id);
    	#	        //$this->unsetMemberDetails($members);
    	#	    endforeach;
    	#	endif;
		$message_type = 'message';
		$arr__id = [];
		$arr__inspMessageIds = InspirationalMessages::select('messageid')->where('message_type', $message_type)->get();//->where('userid', $request->userid)->get();
		if(count($arr__inspMessageIds)) {
			foreach($arr__inspMessageIds as $msg) {
				$arr__id[] = $msg->messageid;
			}
		}


		$messgelist = Allmessage::select('id', 'userid', 'usertoid', 'scheduleid')->whereIn('id', $arr__id)->orderby('isread','ASC')->orderBy('id','DESC')->get();

		if($messgelist){
			foreach($messgelist as $messl){
				$tagged_uid[] = $messl->scheduleid;
			}
		}//$this->_pre($tagged_uid);
    		$schedullist = $this->getUserMessageInsp($request, $tagged_uid, $userid, 1);

    		$response ['success'] = true;
    		$response ['message'] = 'Inspirational list successfully get.';
    		$response ['data']->inspirationalSchedulList = $schedullist;
		endif;
		return response ( $response, 200 );
	}
	private function getUserMessageInsp($request, $userid, $uid, $includeTagged = null)
	{
		$this->uid = $userid;
		//$request->sortField = 'schedule_date';
		//$request->order = 'DESC'
		$sortby = ( $request->sortField ) ? $request->sortField : '0' ;
		$orderby = 'DESC';//($request->orderby) ? $request->orderby : 'DESC';

		$field = 'schedule_messages.id';
		$arr__id = [];
		switch($sortby) {
			case '2': // by date
				$field = 'schedule_messages.schedule_date';
			break;
			case '1': // by sender
				$field = 'u.full_name';
				$orderby = 'ASC';
			break;
			case '3': // unread
"select um.userid, um.scheduleid,  `schedule_messages`.`id`, `u`.`id` as `userid`, `key_id`, `message`,
`read_status`, `schedule_date`, FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2
from unread_user_message um
left JOIN `schedule_messages`  on um.scheduleid = schedule_messages.id
left join `mobileapps` as `u` on `schedule_messages`.`userid` = `u`.`id`
left join `keymoments` as `k` on `schedule_messages`.`key_id` = `k`.`id`
where `schedule_messages`.`userid` in (8, 18) and `schedule_messages`.`deleted` = 0
and `schedule_messages`.`archive` = 0 AND um.userid = 18 order by `schedule_messages`.`id` desc";
				break;
			case '4': // Favorite
				/** */


				$arr__favMessageIds = FavouriteMessages::select('fsm.id')
				->leftjoin('allmessages as m', 'm.id', '=', 'favourite_messages.messageid')
				->leftjoin('schedule_messages as fsm', 'fsm.id', '=', 'm.scheduleid')
				->where('message_type', 'message')->where('m.userid', $request->userid);
				if($includeTagged) {
					$arr__favMessageIds = $arr__favMessageIds->whereIn('fsm.id', $userid);
					$arr__favMessageIds = $arr__favMessageIds->get();
					if(count($arr__favMessageIds) > 0) {
						foreach($arr__favMessageIds as $msg) {
							$arr__id[] = $msg->id;
						}
					} else {
						$arr__id[] = 0;
					}

				} else {
					$arr__favMessageIds = $arr__favMessageIds->whereIn('fsm.userid', $userid);
					$arr__favMessageIds = $arr__favMessageIds->get();
					if(count($arr__favMessageIds) > 0) {
						foreach($arr__favMessageIds as $msg) {
							$arr__id[] = $msg->id;
						}
					} else {
						$arr__id[] = 0;
					}
				}//*/
			$arr__id = array_unique($arr__id);

			//$this->_pre($arr__id);
				break;
			case '5': // Archived

				break;
			case '6': // Keymement
				$field = 'k.title';
				$orderby = 'ASC';
				break;
		}

		//$arr__id = [];



		if($includeTagged) :
			/*$messageids = MessageTaggedUser::whereIn('userid', $userid)->select('messageid', 'userid')->get();
			$messageid = array();
			foreach($messageids as $mess):
				array_push($messageid, $mess->messageid);
			endforeach;*/
			$messageid = $userid;
			$this->messageid = $messageid;
			#$this->_pre($userid, 'HHH');
			$msglist = ScheduleMessage::select('schedule_messages.id', 'u.id AS userid', 'key_id', 'message', 'read_status', 'schedule_date',DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))
			->leftjoin('mobileapps as u', 'schedule_messages.userid', '=', 'u.id')
			->leftjoin('keymoments as k', 'schedule_messages.key_id', '=', 'k.id')
			->with ( array (
				'media_files' => function ($q) {
					$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->latest()->limit(1);
				}))->where(function ($query) {
					$query->whereIn('schedule_messages.id', $this->messageid); //->orWhereIn('id', $this->messageid);
				})->where('schedule_messages.deleted','0');//->orderBy('id','DESC')->get ();
			//->whereIn('id',$tageuser)

		else :
	    	$msglist = ScheduleMessage::select (  'schedule_messages.id', 'u.id AS userid', 'key_id', 'message', 'read_status', 'schedule_date',DB::raw("FROM_UNIXTIME(schedule_date, '%d-%m-%Y %H:%i:%s') AS schedule_date2"))
				->leftjoin('mobileapps as u', 'schedule_messages.userid', '=', 'u.id')
				->leftjoin('keymoments as k', 'schedule_messages.key_id', '=', 'k.id')
				->with ( array (
				'media_files' => function ($q) {
				$q->select ( 'id', 'type_id', 'filepath', DB::raw("CONCAT('".url()."',filepath) AS fileurl"));
				}
				) )->with('keymoment')->with('tagged_users')
				->with(array('messages' => function ($query) {
					$query->where ( 'deleted', 0 )->latest()->limit(1);
					}))->whereIn( 'schedule_messages.userid', $userid )->where('schedule_messages.deleted','0');//->orderBy('id','DESC')->get ();
		//where('schedule_messages.archive', 0)->
	endif;

	// Fav message filter
	if (!empty($arr__id)) {
		$msglist->whereIn('schedule_messages.id', $arr__id);
	}
	// End Fav messge filter
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
if($sortby == '5') { // Filter only Archive
	$msglist = $msglist->where('schedule_messages.archive', 1);
}
else {
	$msglist = $msglist->where('schedule_messages.archive', 0);
}

if($sortby == '3') { // Filter only Unread schedule message
	$msglist = $msglist->leftjoin('unread_user_message as um', 'um.scheduleid', '=', 'schedule_messages.id');
	$msglist = $msglist->where('um.userid', $request->userid);
	$msglist = $msglist->groupBy('schedule_messages.id');
	$msglist = $msglist->orderBy(DB::raw('count(schedule_messages.id)'),'DESC');
}
else {
}



	$msglist = $msglist->orderBy($field, $orderby);//->get();
	if($perpage > 0):
		$msglist = $msglist->offset($offset)->limit($limit);
	endif;
		$msglist = $msglist->get();

	$msglist = $this->_format_schmessage($msglist, $uid);
	return $msglist;

		if($msglist):
		    foreach($msglist as $msgl):

					$dt = $msgl->schedule_date;
					$msgl->schedule_date = date('d-m-Y', $dt);
					$msgl->schedule_time = date('h:i A', $dt);

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
		    endforeach;
		endif;

		return $msglist;
	}
	public function getuidTaggedByPhone($userid, $phone) {
		//
		$memid = [];
/*
		$uids = DB::table('schedule_messages as s')
			->leftjoin('message_tagged_users as tu', 'tu.messageid', '=', 's.id')
			->leftjoin('mobileapps as u', 's.userid', '=', 'u.id')
			->select('s.userid')
			->whereIn('u.mobile_number', $phone)
			#->where('tu.userid', $userid)
			->get();//*/
			$uids = 
			 DB::table('schedule_messages as s')
			->leftjoin('message_tagged_users as tu', 'tu.messageid', '=', 's.id')
			->leftjoin('mobileapps as u', 's.userid', '=', 'u.id')
			->select('s.userid')
			->where(function ($query) use ($userid, $phone) {
							$query->whereIn('u.mobile_number', $phone)
								->orWhere('tu.userid', $userid);
			})/*->
			->whereIn('u.mobile_number', $phone)
			->where('tu.userid', $userid)*/
			->get();//*/
			$tagged_uid = [$userid];
			foreach($uids as $row_uid)  {
				$tagged_uid[] = $row_uid->userid;
			}

			$tagged_uid = array_unique($tagged_uid);

			return $tagged_uid;
	}
	public function FriendsInspirationalList(Request $request)
	{
	    $response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if(trim($request->userid) == ''):
			$response ['success'] = false;
	    $response ['message'] = 'User Id can not be blank.';
		elseif(empty($request->phones)):
		    $response ['success'] = false;
	    	$response ['message'] = 'Phone number can not be blank.';
		else:
			$userid = $request->userid;
			$phones = $request->phones;
			$tagged_uid = $this->getuidTaggedByPhone($userid, $phones);

			$schedullist = $this->getUserMessageInsp($request, $tagged_uid, $userid);

    		$response['success'] = true;
    		$response['message'] = 'Friendspiration list successfully get.';
    		$response['data']->friendsinspirationalSchedulList = $schedullist;

		endif;
		return response ( $response, 200 );
	}

	public function updatePairId(Request $request)
    {

        $Pairid = $request->pairid;
        $UserId = $request->userid;
		$response ['success'] = '';
        $response['message'] = '';
        $response['data'] = (object)array();
        if($UserId == ""):
            $response ['success'] = false;
            $response['message'] = 'User Id required.';
            return response($response, 200);
        else:
            $user_detail=mobileapp::where('id',$UserId)->count();
            if($user_detail < 1):
                $response ['success'] = false;
                $response['message'] = 'User Id invalid.';
                return response($response, 200);
            endif;
        endif;
        if($Pairid ==""):
            $response ['success'] = false;
            $response['message'] = 'Pair Id required.';
            return response($response, 200);
        endif;

        $query = mobileapp::where('id',$UserId)->update(['pairid'=>$Pairid]);
        $memberDetails = $this->getUserDetails($UserId);

        $response ['success'] = true;
        $response['message'] = 'Pair id successfully update.';
        $response['data']->memberDetails = $memberDetails;
        return response($response, 200);
    }
		public function checkEmailExist(Request $request) {
			$response['success'] ='';
			$response['message'] = '';
			$response['data'] = (object)array();
			if(trim($request->email) ==""):
				$response['success'] = false;
				$response['message'] = 'Email address can not blank.';
				return response($response, 200);
			else:
				$checkemail = $this->checkemail($request->email);
				if(!$checkemail):
					$response['success'] = false;
					$response['message'] = 'Invalid email address.';
					return response($response, 200);
				endif;
			endif;

			$status = false;
			$msg = 'Email address available.';
			$mem = mobileapp::where('email',$request->email)->count();
			if($mem > 0) {
				$status = true;
				$msg = 'Email address already exist.';
			}

			$response['success'] = $status;//true;
			$response['message'] = $msg;
			//$response['data']->status = $status;
			return response($response, 200);

		}
}
?>