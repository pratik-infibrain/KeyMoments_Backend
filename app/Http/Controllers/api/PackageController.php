<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Package;
use App\Userpackage;
use App\mobileapp;
use App\Giftsoldier;
use App\Transaction;
use App\Webhook;
use File, DB;

class PackageController extends CommonController {
	public function countpages($path) {
		$pdf = file_get_contents ( $path );
		$number = preg_match_all ( "/\/Page\W/", $pdf, $dummy );
		return $number;
	}
	public function packageList() {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		$packageList = Package::select ( 'id', 'package_name', 'package_price', 'number_of_notes', 'number_of_photos', 'number_of_videos', 'data_limit' )->where ( 'deleted', 0 )->where ( 'status', 1 )->get ();
		if (sizeof ( $packageList ) > 0) :
			$response ['success'] = true;
			$response ['message'] = 'Package list successfully get.';
			$response ['data']->packageList = $packageList;
		 else :
			$response ['success'] = false;
			$response ['message'] = 'no records found.';
		endif;
		return response ( $response, 200 );
	}
	public function changeTopicdStatus(Request $request) {
		$topicId = $request->topicId;
		$isDemoTopic = $request->isDemoTopic;
		$response ['status'] = 200;
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		if ($topicId == "") :
			$response ['status'] = 404;
			$response ['message'] = 'Topic Id required!';
			return response ( $response, 200 );
		 else :
			$topicCount = Topic::where ( 'id', $topicId )->count ();
			if ($topicCount < 1) :
				$response ['status'] = 404;
				$response ['message'] = 'Invalid Topic Id.';
				return response ( $response, 200 );


            endif;
		endif;
		if ($isDemoTopic == "") :
			$response ['status'] = 404;
			$response ['message'] = 'Demo topic status required!';
			return response ( $response, 200 );
		 else :
			$topicarray = array (
					'0',
					'1'
			);
			if (! in_array ( $isDemoTopic, $topicarray )) :
				$response ['status'] = 404;
				$response ['message'] = 'Demo topic status invalid!';
				return response ( $response, 200 );


            endif;
		endif;

		$topicsupdate = Topic::find ( $topicId );
		$topicsupdate->isDemoTopic = $isDemoTopic;
		$topicsupdate->save ();

		$topics = Topic::find ( $topicId );

		$response ['status'] = 200;
		$response ['message'] = 'Change Topic Status Successfully.';
		if ($topics->File != "") :
			$topics->File = url ( '/' ) . '/public/uploads/topics/' . $topics->File;
		 else :
			$topics->File = "";
		endif;
		if ($topics->imagefile != "") :
			$topics->imagefile = url ( '/' ) . '/public/uploads/topics/images/' . $topics->imagefile;
		 else :
			$topics->imagefile = "";
		endif;
		if ($topics->videofile != "") :
			$topics->videofile = url ( '/' ) . '/public/uploads/topics/videos/' . $topics->videofile;
		 else :
			$topics->videofile = "";
		endif;
		if ($topics->CourseId != "") :
			$Course = Course::where ( 'id', $topics->CourseId )->first ();
			$topics->CourseName = $Course->CourseName;
			$studentcount = BuyCourse::where ( 'courseId', $topics->CourseId )->count ();
			$topics->totalenrolledstudent = $studentcount;
		 else :
			$topics->CourseName = "";
			$topics->totalenrolledstudent = 0;
		endif;

		$response ['data']->topicDetails = $topics;
		return response ( $response, 200 );
	}
	/**
	 *
	 * @param unknown $id
	 * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
	 */
	public function userPackageList(Request $request, $id) {

		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim ( $id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );
		endif;
		$arr__package_fields = ['id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit'];
		// 'id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit'
		$arr__user_fields = [
				'id', 'email', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender', 'dateofbirth', 'marital_status', 'education', 'military_status', 'employment', 'short_detail', 'profile_photo'
		];
		// $packageList = Userpackage::with ( 'packages' )->with ( 'user' )->where ( 'deleted', 0 )->where ( 'userid', $id )->get ();
		// $packageList = Userpackage::with ( 'packages' )->where ( 'deleted', 0 )->where ( 'userid', $id )->get ();
		$packageList = Userpackage::with ( array (
				'packages' => function ($query) {
					$query->select ( 'id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit' )->where('deleted', 0);
				}
		) )->with (array(
				'user' => function ($qry) {
					$qry->select ('id', 'email', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender', 'dateofbirth', 'marital_status', 'education', 'military_status', 'employment', 'short_detail', 'profile_photo');
				}
		))->where ( 'deleted', 0 )->where ( 'userid', $id )->get ();

		if (sizeof ( $packageList ) > 0) :
			$response ['success'] = true;
			$response ['message'] = 'Package list successfully get.';
			$response ['data']->packageList = $packageList;
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
	public function userSuggestPackageList(Request $request, $id) {

		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim ( $id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Package id can not blank.';
			return response ( $response, 200 );
		endif;
		$pkgprice = Package::select ('package_price')->where('id', $id)->first();
		$price = 0;
		if (sizeof($pkgprice) > 0) {
			$price = $pkgprice->package_price;
		}

		#return response ( $response, 200 );
		$arr__package_fields = ['id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit'];
		// 'id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit'
		$arr__user_fields = [
				'id', 'email', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender', 'dateofbirth', 'marital_status', 'education', 'military_status', 'employment', 'short_detail', 'profile_photo'
		];
		// $packageList = Userpackage::with ( 'packages' )->with ( 'user' )->where ( 'deleted', 0 )->where ( 'userid', $id )->get ();
		// $packageList = Userpackage::with ( 'packages' )->where ( 'deleted', 0 )->where ( 'userid', $id )->get ();
		//$pkglist = Package::select ($arr__package_fields)->where('package_price', '>', $price)->get();
		$pkglist = Package::select ($arr__package_fields)->where('id', '!=', $id)->where('id', '!=', 1)->get();
		//$response['data']->list = $pkglist;
		//return response ( $response, 200 );
		/*

		$packageList = Userpackage::with ( array (
				'packages' => function ($query) {
				$query->select ( 'id', 'package_name', 'package_price', 'number_of_notes',	'number_of_photos',	'number_of_videos',	'data_limit2' )->where('deleted', 0)->where('package_price', '>', $price);
				}
				) )->with (array(
						'user' => function ($qry) {
						$qry->select ('id', 'email', 'full_name', 'firstname', 'lastname', 'mobile_number', 'gender', 'dateofbirth', 'marital_status', 'education', 'military_status', 'employment', 'short_detail', 'profile_photo');
						}
		))->where ( 'deleted', 0 )->where ( 'userid', $id )->get ();
	*/
		if (sizeof ( $pkglist ) > 0) :
			$response ['success'] = true;
			$response ['message'] = 'Package list successfully get.';
			$response ['data']->packageList = $pkglist;
		else :
			$response ['success'] = false;
			$response ['message'] = 'no records found.';
		endif;
		return response ( $response, 200 );
	}

	public function updateUserPackage(Request $request) {
		$user_id = $request->userid;
		$id = $request->packageid;

		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim ( $id) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Package id can not blank.';
			return response ( $response, 200 );

		endif;
		if (trim ( $request->packageprice ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Package price can not blank.';
			return response ( $response, 200 );

		endif;
		/*if (trim ( $request->discountprice ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Package id can not blank.';
			return response ( $response, 200 );
		endif;
		if (trim ( $request->promocode ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Promocode can not blank.';
			return response ( $response, 200 );
		endif;
		*/
		if(trim($request->giftprice) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Gift price can not blank.';
			return response ( $response, 200 );
		endif;
		if (trim ( $user_id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'User id can not blank.';
			return response ( $response, 200 );

		endif;
		if (trim ( $request->totalprice ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Package total price can not blank.';
			return response ( $response, 200 );

		endif;
		$mobileapp = mobileapp::find($user_id);
		$package = Package::find($id);

		if( trim($user_id) == '' ) { //|| $package->userid != $user_id) {
			$msg = (trim($user_id) == '') ? 'User id can not blank.' : "User's Package record not found";
			$response ['success'] = false;
			$response ['message'] = $msg;
			return response ( $response, 200 );
		}
		$userpackage = new Userpackage();
		/*$userpackage = Userpackage::where('userid', $user_id)->first();
		if(sizeof($userpackage) > 0) {
			$userpackage->userid = $user_id;
			$userpackage->packageid = $id;
			$userpackage->packageprice = $request->packageprice;
			$userpackage->discount = $request->discountprice;
			$userpackage->promocode = $request->promocode;
			$userpackage->totalprice = $request->totalprice;
		}
		 else {*/
		 	$userpackage = new Userpackage();
		 	$userpackage->userid = $user_id;
		 	$userpackage->packageid = $id;
		 	$userpackage->packageprice = $request->packageprice;
		 	$userpackage->discount = $request->discountprice;
		 	$userpackage->promocode = $request->promocode;
		 	$userpackage->totalprice = $request->totalprice;
		 //}
		$userpackage->save();
		#$userpackage_id = $userpackage->id;

		$mobileapp->package = $id;
   // update users space
	  // GB -> MB -> kb 1024 => byte
	 	$package_size = $package->data_limit * 1000 * 1000 * 1000;

		$mobileapp->total_size = $package_size;
		$mobileapp->save();
		$addgiftprice = Giftsoldier::where('userid', $user_id)->first();

		if(sizeof($addgiftprice) > 0) {
			$addgiftprice->giftprice = $request->giftprice;
			$addgiftprice->save();
		} else {
			$addgiftprice = new Giftsoldier();
			$addgiftprice->giftprice = $request->giftprice;
			$addgiftprice->userid = $user_id;
			$addgiftprice->save();
		}

		$response ['success'] = true;
		$response ['message'] = 'Package updated successfully.';
		#$response ['data']->userpackageDetails = $userpackage;
		#$response ['data']->giftPriceDetails = $addgiftprice;
		$memberDetails1 = $this->getUserDetails($user_id);
		$response ['data']->memberDetails = $memberDetails1;

		#$response ['data']->memberDetails = $memberDetails1;

		return response ( $response, 200 );
	}

	public function storePaymentdata(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'user id can not blank.';
			return response ( $response, 200 );
		endif;
		$post_parameters = $request->request->all();
		$data = json_encode($post_parameters);
		$trans = new Transaction();
		$trans->userid = $request->userid;
		$trans->payment_data = $data;
		$trans->save();
		$response ['success'] = true;
		$response ['message'] = 'Payment data saved successfully.';
		return response ( $response, 200 );
		/*'{
			"id": "pi_3KM3yMC3BRO6Qcls004TFkdS",
			"object": "payment_intent",
			"amount": 100,
			"amount_capturable": 0,
			"amount_received": 0,
			"application": null,
			"application_fee_amount": null,
			"automatic_payment_methods": null,
			"canceled_at": null,
			"cancellation_reason": null,
			"capture_method": "automatic",
			"charges": {
				"object": "list",
				"data": [

				],
				"has_more": false,
				"total_count": 0,
				"url": "/v1/charges?payment_intent=pi_3KM3yMC3BRO6Qcls004TFkdS"
			},
			"client_secret": "pi_3KM3yMC3BRO6Qcls004TFkdS_secret_SXGTaeFeWTgQwgrmgjWqq37wn",
			"confirmation_method": "automatic",
			"created": 1643174590,
			"currency": "usd",
			"customer": "cus_L0IBShhmeSvCyD",
			"description": null,
			"invoice": null,
			"last_payment_error": null,
			"livemode": false,
			"metadata": {
			},
			"next_action": null,
			"on_behalf_of": null,
			"payment_method": null,
			"payment_method_options": {
				"card": {
					"installments": null,
					"network": null,
					"request_three_d_secure": "automatic"
				}
			},
			"payment_method_types": [
				"card"
			],
			"processing": null,
			"receipt_email": null,
			"review": null,
			"setup_future_usage": null,
			"shipping": null,
			"source": null,
			"statement_descriptor": null,
			"statement_descriptor_suffix": null,
			"status": "requires_payment_method",
			"transfer_data": null,
			"transfer_group": null
		}';*/
	}

	public function webhook(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();

		$post_parameters = $request->request->all();
		$data = serialize($post_parameters);

		$data = @file_get_contents('php://input');
		$webhook = new Webhook();
		//$trans->userid = $request->userid;
		$webhook->data = $data;
		$webhook->save();
		$response ['success'] = true;
		$response ['message'] = 'Web hook saved successfully.';
		return response ( $response, 200 );
	}

	public function getUserPaymentData(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		if (trim($request->userid) == "") :
			$response ['success'] = false;
			$response ['message'] = 'user id can not blank.';
			return response ( $response, 200 );
		endif;

		$trans = Transaction::where('userid', $request->userid)->orderBy('id','DESC')->first();
		if(sizeof($trans) > 0) {
			$return_data = json_decode($trans->payment_data);
			// @todo Need to check subscription status of the user from webhook data of the user and return subscription_status
			$return_data->subscription_status = true;
			$response['data'] = $return_data;
			$response ['success'] = true;
			$response ['message'] = 'User Transaction details successfully get.';
			return response ( $response, 200 );
		} else {
			$response ['success'] = false;
			$response ['message'] = 'No data found.';
			return response ( $response, 200 );
		}
	}
}

?>