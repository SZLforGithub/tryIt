<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
    	if(isset(Auth::user()->name)){
    		$whoYouAre = Auth::user()->name;
    		$profile = User::where('name', '=', $whoYouAre)->get();
    		return view('profile', ['profile' => $profile]);
    	}
    	else
    		return Redirect('login');
    }

    public function edit(Request $request)
    {
    	$request->all();
    	$whoYouAre = Auth::user()->name;
    	$user = User::where('name', '=', $whoYouAre)->first();

    	if($request->whatThisInput == 'name')
	    	$user->name = $request->name;
    	else if($request->whatThisInput == 'email')
    		$user->email = $request->email;
	    
    	$user->save();
    	return Redirect('profile');
    }
}