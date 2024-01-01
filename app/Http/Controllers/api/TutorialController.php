<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Tutorial;
use File,DB;

class TutorialController extends CommonController
{
    public function countpages($path) 
    { 
      $pdf = file_get_contents($path); 
      $number = preg_match_all("/\/Page\W/", $pdf, $dummy); 
      return $number; 
    }  
    public function tutorialList()
    {
        $response['success'] ='';
        $response['message'] = '';
        $response['data'] = (object)array();
        $tutorialList = Tutorial::select('id','tutorialname','tutorialvideo')->where('deleted',0)->where('status',1)->get();
        if(sizeof($tutorialList) > 0):
            foreach($tutorialList as $tutor):
                if($tutor->tutorialvideo !=""):
                    $tutor->tutorialvideourl = url().'/public/uploads/tutorial/'.$tutor->tutorialvideo;
                else:
                    $tutor->tutorialvideourl = null;
                endif;

            endforeach;    
            $response['success'] = true;
            $response['message'] = 'Tutorial list successfully get.';
            $response['data']->tutorialList = $tutorialList;
        else:
            $response['success'] = false;
            $response['message'] = 'no records found.';
        endif;    
        return response($response, 200);
    }
}   
?>