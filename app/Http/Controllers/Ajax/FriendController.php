<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\friend_relation;
use DB;
use Auth;

class FriendController extends Controller
{
    public function __construst() {
    	$this->middleware('auth');
    }

    public function add(Request $request) {
    	$userId1 = $request->userId1;
    	$userId2 = $request->userId2;

    	$friend_relation = new friend_relation;
    	$friend_relation->userId1 = $userId1;
    	$friend_relation->areFriends = 'add';
    	$friend_relation->userId2 = $userId2;
    	$friend_relation->save();

    	return response()->json(array());
    }

    public function agree(Request $request) {
    	$id = $request->id;
    	$whoYouAre = Auth::user()->id;

    	$friend_relation = friend_relation::where('userId1', '=', $id)->where('userId2', '=', $whoYouAre)->first();
    	$friend_relation->areFriends = 'Y';
    	$friend_relation->save();

    	return response()->json(array());
    }

    public function reject(Request $request) {
		$id = $request->id;
    	$whoYouAre = Auth::user()->id;

    	$friend_relation = friend_relation::where('userId1', '=', $id)->where('userId2', '=', $whoYouAre)->first()->delete();

    	return response()->json(array());
    }

    public function autocomplete(Request $request) {
        $term = $request->term;
        $searchs = DB::table('users')
                ->where('name', 'like', '%'.$term.'%')
                ->take(3)
                ->get();
        foreach ($searchs as $search) {
            $results[] = ['value' => $search->name];
        }
        return response()->json($results);
    }
}
