<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ProfileController extends Controller
{
    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateProfile(Request $request){
        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255|unique:users,name,'.$user->id,
            'email' => 'required|email|max:255|unique:users,name,'.$user->id,
        ]);

        /**
         * storing the input fields name & email in variable $input
         * type array
         **/
        $input = $request->only('name','email');

        /**
         * fetching the user model
         */
        

        /**
         * Accessing the update method and passing in $input array of data
         **/
        $user->update($input);

        /**
         * after everything is done return them pack to /profile/ uri
         **/
        return back();
    }
    public function show()
    {
        $user = Auth::user();
        //print_r($user);
        return view('backend/profile', compact('user'));
    }
}