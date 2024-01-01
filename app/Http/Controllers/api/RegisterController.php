<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Member;
use App\mobileapp;
use App\Childrendetail;
use App\userexicuter;
use App\Promotion;
use App\Userpackage;
use App\Giftsoldier;
use File,DB;

class RegisterController extends CommonController
{
    public function countpages($path)
    {
      $pdf = file_get_contents($path);
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
      return $number;
    }
    public function register(Request $request)
    {
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
        if(trim($request->password)==""):
            $response['success'] = false;
            $response['message'] = 'Password can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->firstname)==""):
            $response['success'] = false;
            $response['message'] = 'Firstname can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->lastname)==""):
            $response['success'] = false;
            $response['message'] = 'Lastname can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->mobile_number)==""):
            $response['success'] = false;
            $response['message'] = 'Mobile number can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->gender)==""):
            $response['success'] = false;
            $response['message'] = 'Gender can not blank.';
            return response($response, 200);
        else:
            $genderarray = array('male','female','other');
            if(!in_array(strtolower($request->gender),$genderarray)):
                $response['success'] = false;
                $response['message'] = 'Invalid gender.';
                return response($response, 200);
            endif;
        endif;
        if(trim($request->dateofbirth)==""):
            $response['success'] = false;
            $response['message'] = 'Date of birth can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->marital_status)==""):
            $response['success'] = false;
            $response['message'] = 'Marital status can not blank.';
            return response($response, 200);
        else:
            $maritalstatusarray = array('Married','Single','Divorced');
            if(!in_array(ucfirst($request->marital_status),$maritalstatusarray)):
                $response['success'] = false;
                $response['message'] = 'Invalid marital status.';
                return response($response, 200);
            endif;
        endif;

        if(trim($request->customer_id)==""):
            $response['success'] = false;
            $response['message'] = 'Customer id can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->education)==""):
            $response['success'] = false;
            $response['message'] = 'Education can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->military_status)==""):
            $response['success'] = false;
            $response['message'] = 'Military status can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->employment)==""):
            $response['success'] = false;
            $response['message'] = 'Employment can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->packageid)==""):
            $response['success'] = false;
            $response['message'] = 'Package can not blank.';
            return response($response, 200);
        endif;
        $memberDetails = mobileapp::with('childrens')->with('executors')->where('deleted',0)->where('email',$request->email)->first();
        if(sizeof($memberDetails) < 1):
            if($request->promocode !=""):
                $promotion = Promotion::where('promotion_code',$request->promocode)->first();
                if($promotion):
                    if((strtotime($promotion->valid_form_date) >= strtotime(date('Y-m-d'))) && (strtotime($promotion->valid_to_date) <= strtotime(date('Y-m-d')))):
                        $response['success'] = false;
                        $response['message'] = 'Expired promotion code.';
                        return response($response, 200);
                    endif;
                else:
                    $response['success'] = false;
                    $response['message'] = 'Invalid promotion code.';
                    return response($response, 200);
                endif;
            endif;

            //$dateofbirth = str_replace("/", "-", $request->dateofbirth);
            $dateofbirthtemp = explode('/',$request->dateofbirth);
            $dateofbirth = $dateofbirthtemp[1].'-'.$dateofbirthtemp[0].'-'.$dateofbirthtemp[2];
            $users = new mobileapp();
            $users->email = $request->email;
            $users->password = $request->password;
            $users->full_name = $request->firstname.' '.$request->lastname;
            $users->firstname = $request->firstname;
            $users->lastname = $request->lastname;
            $users->mobile_number = $request->mobile_number;
            $users->gender = strtolower($request->gender);
            $users->dateofbirth = date('Y-m-d',strtotime($dateofbirth));
            $users->marital_status = ucfirst($request->marital_status);
            $users->education = $request->education;
            $users->military_status = $request->military_status;
            $users->employment = $request->employment;
            $users->package = $request->packageid;
            $users->customer_id = $request->customer_id;
            // Default set to acive
            $users->status = 1;
            $users->save();
            $insertedId = $users->id;
            if($request->childrendetails):
                foreach($request->childrendetails as $child):
                    $chidlren = new Childrendetail();
                    $chidlren->name = $child['name'];
                    $chidlren->email = $child['email'];
                    $chidlren->phone = $child['phone'];
                    $chidlren->userid = $insertedId;
                    $chidlren->save();
                endforeach;
            endif;
            if($request->executors):
                foreach($request->executors as $exec):
                    $execer = new userexicuter();
                    $execer->name = $exec['name'];
                    $execer->email = $exec['email'];
                    $execer->phone = $exec['phone'];
                    $execer->user_id = $insertedId;
                    $execer->save();
                endforeach;
            endif;
            if($request->packageid !=""):
                $pacge = new Userpackage();
                $pacge->packageid = $request->packageid;
                $pacge->packageprice = $request->packageprice;
                $pacge->discount = $request->discountprice;
                $pacge->promocode = $request->promocode;
                $pacge->totalprice = $request->totalprice;
                $pacge->userid = $insertedId;
                $pacge->save();
            endif;
            if($request->giftprice !=""):
                $addgiftprice = new Giftsoldier();
                $addgiftprice->giftprice = $request->giftprice;
                $addgiftprice->userid = $insertedId;
                $addgiftprice->save();
            endif;
            $memberDetails1 = mobileapp::with('childrens')->with('executors')->where('deleted',0)->where('id',$insertedId)->first();
            $response['success'] = true;
            $response['message'] = 'Register Successfully.';
            $loginstatustext = $this->loginstatus($memberDetails1->loginstatus);
                unset($memberDetails1['Otp']);
                unset($memberDetails1['otpdatetime']);
                unset($memberDetails1['otpstatus']);
                unset($memberDetails1['loginstatus']);
                unset($memberDetails1['isDisabled']);
                unset($memberDetails1['status']);
                unset($memberDetails1['deleted']);
                unset($memberDetails1['updated_at']);
                unset($memberDetails1['created_at']);
                $memberDetails1->loginstatus = $loginstatustext.' Login';
            $response['data']->memberDetails = $memberDetails1;
        else:
            $response['success'] = false;
            $response['message'] = 'Your email already exits please login.';
        endif;
        return response($response, 200);
    }
}
?>