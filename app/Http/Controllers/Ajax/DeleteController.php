<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;

class DeleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function destroy(Request $request) {
    	$id = $request->id;
    	$post = Post::find($id);
    	$post->delete();
    	
    	return response()->json(array());
    }
}
