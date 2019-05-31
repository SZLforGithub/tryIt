<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
    public function __construst() {
    	$this->middleware('auth');
    }

    public function search(Request $request) {
    	$term = $request->search;
    	$searchs = DB::table('users')
    			->select('users.id', 'users.name', 'photos.smallSource')
                ->where('name', 'like', '%'.$term.'%')
                ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
                ->get();
         return view('search', ['searchs' => $searchs]);
    }
}
