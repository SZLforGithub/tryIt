<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Post_backup;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('home', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        //存入posts & post_backups資料表
        $post = new post;
        $post->poster = Auth::user()->name;
        $content = nl2br($request->content);
        $post->content = $content;
        $post->save();

        $post_backup = new post_backup;
        $post_backup->poster = Auth::user()->name;
        $post_backup->content = $content;
        $post_backup->save();

        return Redirect('home');
    }

    public function destroy($id)
    {
       post::destroy($id);
       return Redirect('home');
    }
}
