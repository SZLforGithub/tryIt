<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\photo;
use Auth;
use View;

class ProfileController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }
    
    public function index(Request $request)
    {
    	if(isset(Auth::user()->name)){
    		$whoYouAre = Auth::user()->name;
    		$profile = User::where('name', '=', $whoYouAre)->first();
    		$sourcePath = $profile->shot_path;
    		$photo = Photo::where('path', '=', $sourcePath)->first();
    		if($photo == null)
    			$editSource = null;
    		else
    			$editSource = $photo->editSource;
    		
    		return view('profile', ['editSource' => $editSource]);
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