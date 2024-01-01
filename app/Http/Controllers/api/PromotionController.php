<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Promotion;
use App\Package;
use App\mobileapp;
use App\Giftsoldier;
use File,DB;

class PromotionController extends CommonController
{
    public function countpages($path) 
    { 
      $pdf = file_get_contents($path); 
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy); 
      return $number; 
    }  
    public function promotionlist()
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        $today = date('Y-m-d');
        $promotionList = Promotion::whereDate('valid_form_date','<=', $today)
            ->whereDate('valid_to_date','>=', $today)->where('deleted',0)->where('status',1)->get();
        if(sizeof($promotionList) > 0):
            $response['success'] = true;
            $response['message'] = 'Promotion list successfully get.';
            if($promotionList):
                foreach($promotionList as $proL):
                    unset($proL['status']);
                    unset($proL['deleted']);
                    unset($proL['updated_at']);
                    unset($proL['created_at']);
                endforeach;
            endif; 
            $response['data']->promotionList = $promotionList;
        else:
            $response['success'] = false;
            $response['message'] = 'no records found.';
        endif;    
        return response($response, 200);
    }
    public function applyPromocode(Request $request)
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        $today = date('Y-m-d');
        if(trim($request->packageid)==""):
            $response['success'] = false;
            $response['message'] = 'Package id can not be blank.';
            return response($response, 200);
        else:
            $checkpackagevalid = Package::where('id',$request->packageid)->where('deleted',0)->where('status',1)->count();
            if($checkpackagevalid < 1):
                $response['success'] = false;
                $response['message'] = 'Invalid package.';
                return response($response, 200);
            endif;    
        endif; 
        if(trim($request->promotioncode)==""):
            $response['success'] = false;
            $response['message'] = 'Promotion code can not be blank.';
            return response($response, 200);
        else:
            $checkpackagevalid = Promotion::where('promotion_code',$request->promotioncode)->where('deleted',0)->where('status',1)->count();
            if($checkpackagevalid < 1):
                $response['success'] = false;
                $response['message'] = 'Invalid promotion code.';
                return response($response, 200);
            else:
                $promotiondetials = Promotion::where('promotion_code',$request->promotioncode)->where('deleted',0)->where('status',1)->first();  
                if((strtotime($promotiondetials->valid_form_date) > strtotime($today)) || (strtotime($promotiondetials->valid_to_date) < strtotime($today))):
                    $response['success'] = false;
                    $response['message'] = 'Expired promotion code.';
                    return response($response, 200);
                else:
                    $packagedetials = Package::where('id',$request->packageid)->where('deleted',0)->where('status',1)->first();
                    $packageprice = $packagedetials->package_price;
                    $discounttype = $promotiondetials->type;
                    if($discounttype=='Percentage'):
                        $valueper = $promotiondetials->value;
                        $discountprice = ($valueper * $packageprice)/100;
                        $finalprice = $packageprice - $discountprice;
                    elseif($discounttype=='Amount'):
                        $valueamount = $promotiondetials->value;
                        $discountprice = $valueamount;
                        $finalprice = $packageprice - $valueamount;
                        if($finalprice < 0):
                            $discountprice = $packageprice;
                            $finalprice = '0';
                        endif;    
                    endif; 
                    $applypromotionprice = array(
                        'packageprice'=>number_format((float)$packageprice,2),
                        'discountprice'=>number_format((float)$discountprice,2),
                        'totalprice'=>number_format((float)$finalprice,2)
                    );
                    $response['success'] = true;
                    $response['message'] = 'Promocode applied successfully.';
                    $response['data']->applyPromodcodeDetail = $applypromotionprice;
                    return response($response, 200);
                endif;  
            endif;
        endif;    
    }
    public function applyGiftsoldier(Request $request)
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        $today = date('Y-m-d');
        if(trim($request->giftprice)==""):
            $response['success'] = false;
            $response['message'] = 'Gift price can not be blank.';
            return response($response, 200);
        endif;
        if(trim($request->userid)==""):
            $response['success'] = false;
            $response['message'] = 'User id can not be blank.';
            return response($response, 200);
        else:
            $checkuservalid = mobileapp::where('id',$request->userid)->where('deleted',0)->where('status',1)->count();
            if($checkuservalid < 1):
                $response['success'] = false;
                $response['message'] = 'Invalid member.';
                return response($response, 200);
            else:
                $addgiftprice = new Giftsoldier();
                $addgiftprice->giftprice = $request->giftprice;
                $addgiftprice->userid = $request->userid;
                $addgiftprice->save();
                $giftsoldierDetails = Giftsoldier::with('memberDetails')->where('id',$addgiftprice->id)->where('deleted',0)->where('status',1)->first();

                unset($giftsoldierDetails['status']);
                unset($giftsoldierDetails['deleted']);
                unset($giftsoldierDetails['updated_at']);
                unset($giftsoldierDetails['created_at']);
                $giftsoldierDetails->giftprice=number_format($giftsoldierDetails->giftprice,2);
                $response['success'] = true;
                $response['message'] = 'Apply gift a soldier successfully.';
                $response['data']->giftsoldierDetails = $giftsoldierDetails;
                return response($response, 200);
            endif;    
        endif; 
    }
    
	public function deleteGiftSoldier(Request $request) {
		$response ['success'] = '';
		$response ['message'] = '';
		$response ['data'] = ( object ) array ();
		
		if (trim ( $request->id ) == "") :
			$response ['success'] = false;
			$response ['message'] = 'Gift soldier id can not blank.';
			return response ( $response, 200 );
   	endif;
		
		// Delete children
		DB::table ( 'giftsoldiers' )->where ( 'id', $request->id )->update ( array (
				'deleted' => 1 
		) );
		;
		
		$response ['success'] = true;
		$response ['message'] = 'Gift soldier deleted successfully.';
		
		return response ( $response, 200 );
	}
}   
?>