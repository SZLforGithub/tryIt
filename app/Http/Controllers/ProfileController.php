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
    	$whoYouAre = Auth::user()->name;
    	$request->all();
    	$user = User::where('name', '=', $whoYouAre)->first();
    	$user->name = $request->name;
    	$user->save();

    	return Redirect('profile');
    }
}