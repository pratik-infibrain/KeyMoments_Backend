<?php
namespace App\Http\Controllers\Auth;
use Auth;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
   /*
   |--------------------------------------------------------------------------
   | Registration & Login Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the registration of new users, as well as the
   | authentication of existing users. By default, this controller uses
   | a simple trait to add these behaviors. Why don't you explore it?
   |
   */
	protected $redirectPath = '/backend';
	protected $loginPath = '/auth/login';

   use AuthenticatesAndRegistersUsers, ThrottlesLogins;

   /**
    * Create a new authentication controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
   }

   /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
   protected function validator(array $data)
   {
     return Validator::make($data, [
         'name' => 'required|max:255',
         'email' => 'required|email|max:255|unique:users',
         'password' => 'required|confirmed|min:6',
     ]);
   }

   /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return User
    */
  protected function create(array $data)
  {
    return User::create([
       'name' => $data['name'],
       'email' => $data['email'],
       'password' => bcrypt($data['password']),
       'firstname' => $data['firstname'],
       'lastname' => $data['lastname'],
       'phone' => $data['phone']
   ]);
  }
  public function logout()
  {
    Auth::logout();
    return redirect('/auth/login');
    /*
    Auth::guard($this->getGuard())->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    */
  }
  public function resetpassword()
  {
    $return_data = array();
    $return_data['site_title'] = trans('Reset Password') . ' | ' . $this->data['site_title'];
    return view('auth/resetpassword', $return_data);
  }
   public function Postresetpassword(Request $request)
  {
    $return_data = array();
    $return_data['site_title'] = trans('Reset Password') . ' | ' . $this->data['site_title'];
   
    $this->validate($request, [
        'email' => 'required|exists:users,email',
    ]);
    $admin_user = User::where('email', $request->email)->first();
    // print_r($admin_user);
    $site_email = 'admin@gmail.com';
    $site_name = "Student Portal";
    $headers = 'Content-type: text/html;<br>From: '.$site_email;
    
    $link = url().'/auth/confirmresetpswd/'.base64_encode($admin_user->id);
    $message = 'hello '.$admin_user->name.', <br>Your password reset link. <a href="'.$link.'" target="_blank">Click Here</a>';
    mail($email,'Password successfully reset',$message,$headers);
    return $link;
  }
  public function confirmresetpswd($ids)
  {
      $userids =  base64_decode($ids);
      $return_data = array();
      $return_data['site_title'] = trans('Confirm Password Link') . ' | ' . $this->data['site_title'];
      $return_data['userids']  = $userids;
      
       //return view('category.index', $data);
      return view('auth/resetpasswordfrom',$return_data);

  }
  public function resetpasswordform(Request $request)
  {
      $return_data = array();
      $return_data['site_title'] = trans('Change Password') . ' | ' . $this->data['site_title'];
      $return_data['userids']  = $request->userids;
      $this->validate($request, [
          'password' => 'required|min:6',
      ]);
      $users = User::find($request->userids);
      $users->password = Hash::make($request->password);
      $users->save();
      
        return redirect('/auth/login')->with('success_message', trans('Updated Successfully'));
     
      //return view('auth/login', $return_data);
  }
}
