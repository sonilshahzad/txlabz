<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use View;
use Illuminate\Http\Request;
use Validator;
use Auth;
use AuthenticatesUsers;
use App\User;

class LoginController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function checkLogin(Request $request)
    {
     //    $this->validate($request,[
    	// 	'email' => 'required|email',
    	// 	'password' => 'required|min:5'
    	// ]);

    	$user_data = array(
    		'email' => $request->get('email'),
    		'password' => $request->get('password')
    	);

    	if(Auth::attempt($user_data))
    	{
    		return redirect('/');
    	}
    	else{
            toastr()->error('Wrong Login Details!',' ',['showDuration'=>500,'closeButton'=>true,'progressBar'=>false,'positionClass'=> 'toast-top-right']);
    		return back();
    	}
    }
    
    function logout(){
    	Auth::logout();
        toastr()->success('Logged Out Successfully!',' ',['showDuration'=>500,'closeButton'=>true,'progressBar'=>false,'positionClass'=> 'toast-top-right']);
    	return redirect('/');
    }
}