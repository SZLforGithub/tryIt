<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Auth;
use App\Post;

class CommentController extends Controller
{
    public function create(Request $request, $id) {
    	$comment = new Comment;
    	$comment->postId = $id;
    	$comment->userId = Auth::user()->id;
    	$comment->content = nl2br($request->updateComment);
    	$comment->save();

    	return Redirect('home');
    }

    public function allComments(Request $request) {
    	$postId = $request->id;
    	$post = Post::where('id', '=', $postId)->first();
    	$comments = $post->getAllComments()
    					 ->select('users.name', 'photos.smallSource', 'content')
    					 ->join('users', 'userId', '=', 'users.id')
    					 ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
    					 ->get();

    	foreach ($comments as $temp) {
    		if($temp->smallSource != null)
				$temp->smallSource = asset($temp->smallSource);
    	}

    	return response()->json(array(
    		'comments' => $comments
    	));
    }
}
