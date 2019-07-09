<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\like;
use App\post;

class LikeController extends Controller
{
    public function like(Request $request) {
    	$userId = Auth::user()->id;
    	$postId = $request->id;
    	like::firstOrCreate(
    		['userId' => $userId, 'postId' => $postId]
    	);

    	//return response()->json(array());
    }

    public function unlike(Request $request) {
    	post::find($request->id)
    		->like(Auth::user()->id)
    		->delete();
    }

    public function wholikes(Request $request) {
    	$postId = $request->id;
    	$post = Post::where('id', '=', $postId)->first();
		$peopleWhoLikeThisPost = $post->getAllLikes()
									  ->select('users.name', 'photos.smallSource')
									  ->join('users', 'userId', '=', 'users.id')
									  ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
									  ->get();
		foreach($peopleWhoLikeThisPost as $temp) {
            if(!is_null($temp->smallSource))
                $temp->smallSource = asset($temp->smallSource);
		}
    	return response()->json(array(
    		'peopleWhoLikeThisPost' => $peopleWhoLikeThisPost
    	));
    }
}
