<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Keymoment;
use App\Package;
use App\mobileapp;
use App\Giftsoldier;
use File,DB;

class KeymomentController extends CommonController
{
    public function countpages($path)
    {
      $pdf = file_get_contents($path);
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
      return $number;
    }
    public function keymomentlist()
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        $today = date('Y-m-d');
        $promotionList = Keymoment::select('id', 'title')->where('deleted',0)->where('status',1)->get();
        if(sizeof($promotionList) > 0):
            $response['success'] = true;
            $response['message'] = 'Keymoment list successfully get.';

            $response['data']->keymomentList = $promotionList;
        else:
            $response['success'] = false;
            $response['message'] = 'no records found.';
        endif;
        return response($response, 200);
    }

    public function getUserkeymomentList(Request $request)
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        if(trim($request->userid) == ''){
			$response ['success'] = false;
            $response ['message'] = 'User Id can not be blank.';
        }
        $userid = $request->userid;
        $keys = DB::table('keymoments as k')
        ->leftjoin('schedule_messages as tu', 'tu.key_id', '=', 'k.id')
        ->select('k.id', 'k.title')//, 'tu.message', 'tu.id as messageid')
        ->where('tu.userid', $userid)
        ->distinct()
        ->get();//*/
        #$this->_pre($keys);die;
        //$promotionList = Keymoment::select('id', 'title')->where('deleted',0)->where('status',1)->get();
        if(sizeof($keys) > 0):
            $response['success'] = true;
            $response['message'] = 'Keymoment list successfully get.';

            $response['data']->keymomentList = $keys;
        else:
            $response['success'] = false;
            $response['message'] = 'no records found.';
        endif;
        return response($response, 200);
    }

}
?>