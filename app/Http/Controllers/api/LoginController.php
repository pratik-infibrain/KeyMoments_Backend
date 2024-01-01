<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Member;
use App\mobileapp;
use App\Childrendetail;
use App\userexicuter;
use File,DB;

class LoginController extends CommonController
{
    public function countpages($path)
    {
      $pdf = file_get_contents($path);
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
      return $number;
    }
    public function normallogin(Request $request)
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        $error = 1;
        if(trim($request->email) ==""):
            $response['success'] = false;
            $response['message'] = 'Email address can not blank.';
            $error = 2;
            return response($response, 200);
        else:
            $checkemail = $this->checkemail($request->email);
            if(!$checkemail):
                $response['success'] = false;
                $response['message'] = 'Invalid email address.';
                $error = 2;
                return response($response, 200);
            endif;
        endif;
        if(trim($request->password)==""):
            $response['success'] = false;
            $response['message'] = 'Password can not blank.';
            $error = 2;
            return response($response, 200);
        endif;
        if($error=='1'):
            $memberDetails = mobileapp::with('childrens')->with('executors')->where('deleted',0)->where('email',$request->email)->first();
            if(sizeof($memberDetails)>0):
                if($memberDetails->status == '0'):
                    $response['success'] = false;
                    $response['message'] = 'Your account has been inactive please contact to admin.';
                    return response($response, 200);
                elseif($memberDetails->isDisabled == 'Yes'):
                    $response['success'] = false;
                    $response['message'] = 'Your account has been disabled please contact to admin.';
                    return response($response, 200);
                endif;
                $loginstatustext = $this->loginstatus($memberDetails->loginstatus);
                if($memberDetails->loginstatus =='0'):
                    //if(Hash::check($request->password,$memberDetails->password)):
                    if($request->password==$memberDetails->password):
                        unset($memberDetails['Otp']);
                        unset($memberDetails['otpdatetime']);
                        unset($memberDetails['otpstatus']);
                        unset($memberDetails['loginstatus']);
                        unset($memberDetails['isDisabled']);
                        unset($memberDetails['status']);
                        unset($memberDetails['deleted']);
                        unset($memberDetails['updated_at']);
                        unset($memberDetails['created_at']);
                        $response['success'] = true;
                        $response['message'] = 'Memeber successfully login.';
                        $memberDetails ['profile_photo'] = ($memberDetails ['profile_photo']) ? url() . '/' . $memberDetails ['profile_photo'] : '';
                        $memberDetails->loginstatus = $loginstatustext.' Login';
                        $response['data']->memberDetails = $memberDetails;
                    else:
                        $response['success'] = false;
                        $response['message'] = 'Your password does not match.';
                    endif;
                else:
                    $response['success'] = false;
                    $response['message'] = 'Your email register as a '.$loginstatustext.' login. Please login using '.$loginstatustext.' id.';
                endif;
            else:
                $response['success'] = false;
                $response['message'] = 'Please check your email address.';
            endif;
            return response($response, 200);
        endif;

    }
    public function sociallogin(Request $request)
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        if(trim($request->firstname)==""):
            $response['success'] = false;
            $response['message'] = 'Firstname can not blank.';
            return response($response, 200);
        endif;
        if(trim($request->email) ==""):
            $response['success'] = false;
            $response['message'] = 'Email address can not blank.';
            $error = 2;
            return response($response, 200);
        else:
            $checkemail = $this->checkemail($request->email);
            if(!$checkemail):
                $response['success'] = false;
                $response['message'] = 'Invalid email address.';
                $error = 2;
                return response($response, 200);
            endif;
        endif;
        $logintypearray = array('1','2','3','4','5');
        if(!in_array($request->logintype,$logintypearray)):
            $response['success'] = false;
            $response['message'] = 'Invalid login type. Please enter valid type.';
            $error = 2;
            return response($response, 200);
        endif;

            $memberDetails = mobileapp::with('childrens')->with('executors')->where('deleted',0)->where('email',$request->email)->first();
            if(sizeof($memberDetails)>0):
                if($memberDetails->status == '0'):
                    $response['success'] = false;
                    $response['message'] = 'Your account has been inactive please contact to admin.';
                    return response($response, 200);
                elseif($memberDetails->isDisabled == 'Yes'):
                    $response['success'] = false;
                    $response['message'] = 'Your account has been disabled please contact to admin.';
                    return response($response, 200);
                endif;
                $loginstatustext = $this->loginstatus($memberDetails->loginstatus);
                if($memberDetails->loginstatus == $request->logintype):

                    unset($memberDetails['Otp']);
                    unset($memberDetails['otpdatetime']);
                    unset($memberDetails['otpstatus']);
                    unset($memberDetails['loginstatus']);
                    unset($memberDetails['isDisabled']);
                    unset($memberDetails['status']);
                    unset($memberDetails['deleted']);
                    unset($memberDetails['updated_at']);
                    unset($memberDetails['created_at']);
                    $memberDetails ['profile_photo'] = ($memberDetails ['profile_photo']) ? url() . '/' . $memberDetails ['profile_photo'] : '';
                    $response['success'] = true;
                    $response['message'] = 'Memeber successfully login.';
                    //$memberDetails->profileimageurl = url().'/public/uploads/profileimage/'.$memberDetails->profileimage;
                    $memberDetails->loginstatus = $loginstatustext.' Login';
                    $response['data']->memberDetails = $memberDetails;

                else:
                    $response['success'] = false;
                    $response['message'] = 'Your email register as a '.$loginstatustext.' login. Please login using '.$loginstatustext.' id.';
                endif;
            else:

                $users = new mobileapp();
                $users->full_name = $request->firstname;
                $users->email = $request->email;
                // Default set to active
                $users->status = 1;
                $users->loginstatus = $request->logintype;
                $users->save();
                $insertedId = $users->id;
                $memberDetails = mobileapp::with('childrens')->with('executors')->where('deleted',0)->where('id',$insertedId)->first();
                $loginstatustext = $this->loginstatus($memberDetails->loginstatus);
                unset($memberDetails['Otp']);
                unset($memberDetails['otpdatetime']);
                unset($memberDetails['otpstatus']);
                unset($memberDetails['loginstatus']);
                unset($memberDetails['isDisabled']);
                unset($memberDetails['status']);
                unset($memberDetails['deleted']);
                unset($memberDetails['updated_at']);
                unset($memberDetails['created_at']);
                $memberDetails ['profile_photo'] = ($memberDetails ['profile_photo']) ? url() . '/' . $memberDetails ['profile_photo'] : '';
                $memberDetails->loginstatus = $loginstatustext.' Login';
                $response['data']->memberDetails = $memberDetails;
                $response['success'] = true;
                $response['message'] = 'Member successfully register.';
            endif;

        return response($response, 200);
    }
}
?>