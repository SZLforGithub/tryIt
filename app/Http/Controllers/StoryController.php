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
    	$posts = DB::select(
            "   SELECT posts.id, posts.poster, posts.content, posts.created_at, posts.updated_at, post_photos.photoId01, photos.path
                FROM posts
                LEFT JOIN post_photos ON posts.id = post_photos.postId
                LEFT JOIN photos ON post_photos.photoId01 = photos.id
                WHERE poster = '$whoYouAre'
        ");

    	return view('stories',  ['posts' => $posts], ['photoPath' => $photoPath]);
    }
}
