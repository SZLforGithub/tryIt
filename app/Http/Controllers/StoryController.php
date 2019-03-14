<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use App\post;
use App\photo;
use Auth;
use DB;


class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$whoYouAre = Auth::user()->name;
    	$user = User::where('name', '=', $whoYouAre)->first();
    	$photoPath = Photo::where('path', '=', $user->shot_path)->first();
        $posts = POST::all();
        $posts = $posts->toArray();

        $photos = DB::table('post_photos')
                     ->select('post_photos.postId', 'post_photos.photoId', 'photos.path')
                    ->leftJoin('photos', 'post_photos.photoId', '=', 'photos.id')
                    ->get();

    	return view('stories',  ['posts' => $posts, 'photos' => $photos, 'photoPath' => $photoPath]);
    }
}
