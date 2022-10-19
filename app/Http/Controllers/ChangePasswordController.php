<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function changePassword()
    {
        return view('changepassword');
    }
    
    public function processPasswordChange() {
        
        if (!(Hash::check(request()->input('current-password'), auth()->user()->password))) {
            //dd(request()->input('current-password'));
            // The passwords dont match
            return redirect()->back()->with("error","Your current password does not match with the password you provided. Please try again.");
        }
        else if(strcmp(request()->input('current-password'), request()->input('password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        else if(strcmp(request()->input('password_confirmation'), request()->input('password')) !== 0){
            return redirect()->back()->with("error","New Password does not match with your confirmation password.");
       
        }
        //dd(strcmp(request()->input('password_confirmation'), request()->input('password')));
        $validatedData = request()->validate([
            'current-password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        //Change Password
        $user = auth()->user();
        $hashedPass = bcrypt(request()->input('password'));;

        $updateRes = DB::table('users')
                    ->where('id', $user->id)
                    ->update(array('password' => $hashedPass));

        return redirect()->back()->with("success","Password changed successfully !");
        
    }
}
