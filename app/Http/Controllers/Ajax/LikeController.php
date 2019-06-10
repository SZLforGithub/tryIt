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

    	//return response()->json(array());
    }
}
