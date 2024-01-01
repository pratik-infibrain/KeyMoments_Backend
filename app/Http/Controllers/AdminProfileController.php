<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use View;
use Hash;

class AdminProfileController extends CommonController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */ 
    
     public function getReset($token = null) {
        $return_data = array();
        $return_data['site_title'] = $this->data['site_title'];
		$return_data['page_condition'] = 'change_password_page';
		$return_data['site_title'] = trans('Change Password') . ' | ' . $this->data['site_title'];
        return view('backend.reset',array_merge($this->data, $return_data));
    }
    public function postReset(Request $request){   
		$this->validate($request, [
				'oldpassword' => 'required',
                'password' => 'required|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'required',
		],['oldpassword.required' => 'Old password is required.',
            'password.required' => 'Password is required.',
            'password_confirmation.required' => 'Retype new password is required.',
            'password.same' => 'New password and retype new password must be same.'
        ]);
        $user = \Auth::user(); 
        if (Hash::check($request->oldpassword, $user->password)) 
        { 
           $credentials = $request->only(
                'password', 'password_confirmation'
            );
            
            $user->password = bcrypt($credentials['password']);
            $user->save();
            $user_id = $this->login_user_id;
            $class = config('constants.ADMIN_PANEL');
            $this->log_insert("Password updated" ,$user_id, $_REQUEST,$class);  
            return redirect(route('admin.changepassword'))->with('success_message', trans('Password Change Successfully'));

        } else {
            return redirect(route('admin.changepassword'))->with('error_message', trans('Old Password does not match!'));
        }
		
	}
}
