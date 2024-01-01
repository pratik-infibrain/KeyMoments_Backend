<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Pages;
use File,DB;

class CmsController extends CommonController
{
    public function countpages($path) 
    { 
      $pdf = file_get_contents($path); 
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy); 
      return $number; 
    }  
    public function PrivacyPolicy()
    {
        $return_data = array();
        $pageList = Pages::where('url_title', 'privacy-policy')->first();
        $return_data['page'] = $pageList;
        //$return_data['page_condition'] = 'Privacy Policy';
		$return_data['site_title'] = trans('Privacy Policy');
		return view('privacypolice', array_merge($this->data, $return_data));
        
       
        return response($response, 200);
    }
     public function TermConditions()
    {
        $return_data = array();
        $pageList = Pages::where('url_title', 'terms-and-conditions')->first();
        $return_data['page'] = $pageList;
        //$return_data['page_condition'] = 'Privacy Policy';
		$return_data['site_title'] = trans('Terms and Conditions');
		return view('termandconditions', array_merge($this->data, $return_data));
        
       
        return response($response, 200);
    }
    
}   
?>