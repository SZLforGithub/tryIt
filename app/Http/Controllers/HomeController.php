<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Post_backup;
use App\photo;
use App\post_photo;
use Auth;
use Storage;
use DB;

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
        $posts = DB::select(
            '   SELECT posts.id, posts.poster, posts.content, posts.created_at, posts.updated_at, post_photos.photoId01, photos.path
                FROM posts
                LEFT JOIN post_photos ON posts.id = post_photos.postId
                LEFT JOIN photos ON post_photos.photoId01 = photos.id
        ');
        //dd($posts);
        return view('home', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        // 存入posts & post_backups資料表
        $post = new post;
        $post->poster = Auth::user()->name;
        $content = nl2br($request->content);
        $post->content = $content;
        $post->save();

        $post_backup = new post_backup;
        $post_backup->poster = Auth::user()->name;
        $post_backup->content = $content;
        $post_backup->save();

        // 處理圖片
        // TODO uploads more than one photos?
        if ($request->file('photoForPost') != null){
            // 獲取photo資訊
            $photo = $request->file('photoForPost');
            $ext = $photo->getClientOriginalExtension();
            // 上傳圖片並將路徑存入photos資料表
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' .$ext;
            $path = Storage::putfileAs('public', $photo, $filename);
            $sourcePath = Storage::url($path);
            $whoYouAre = Auth::user()->name;
            $photo = new photo;
            $photo->path = $sourcePath;
            $photo->user = $whoYouAre;
            $photo->save();
            // 將postsId & photoId存入post_photos資料表
            $post_photo = new post_photo;
            $post_photo->postId = $post->id;
            $post_photo->photoId01 = $photo->id;
            $post_photo->save();
        }

        return Redirect('home');
    }

    public function edit(Request $request, $id)
    {
        $post = post::find($id);
        $content = nl2br($request->content);
        $post->content = $content;
        $post->save();

        $post_backup = post_backup::find($id);
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
