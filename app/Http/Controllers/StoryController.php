<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use App\post;
use App\photo;
use Auth;
use DB;
use Exception;


class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($whoYouAre)
    {
    	try {
    		$user = User::where('name', '=', $whoYouAre)->first();
	    	$photoPath = Photo::where('path', '=', $user->shot_path)->first();
	        $posts = POST::all();
	        $posts = $posts->toArray();

	        $photos = DB::table('post_photos')
	                     ->select('post_photos.postId', 'post_photos.photoId', 'photos.path')
	                    ->leftJoin('photos', 'post_photos.photoId', '=', 'photos.id')
	                    ->get();

	    	return view('stories',  ['posts' => $posts, 'photos' => $photos, 'photoPath' => $photoPath]);
    	} catch (Exception $e) {
    		dd($e);
    	}
    	
    }
}
