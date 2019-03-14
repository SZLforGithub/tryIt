<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;
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
        $posts = POST::all();
        $posts = $posts->toArray();

        $photos = DB::table('post_photos')
                     ->select('post_photos.postId', 'post_photos.photoId', 'photos.path')
                    ->leftJoin('photos', 'post_photos.photoId', '=', 'photos.id')
                    ->get();
        //dd($photos);
        return view('home', ['posts' => $posts], ['photos' => $photos]);
    }

    public function create(Request $request)
    {
        $request = $request::all();

        // 存入posts & post_backups資料表
        $post = new post;
        $post->poster = Auth::user()->name;
        $content = nl2br($request['content']);
        $post->content = $content;
        $post->save();

        $post_backup = new post_backup;
        $post_backup->poster = Auth::user()->name;
        $post_backup->content = $content;
        $post_backup->save();

        // 處理圖片
        if (isset($request['photoForPost'])){
            foreach ($request['photoForPost'] as $key => $photoForPost) {
                // 獲取photo資訊
                $photo = $photoForPost;
                $ext = $photo->getClientOriginalExtension();
                // 上傳圖片並將路徑存入photos資料表
                $filename = date('Y-m-d-H-i-s') . '-' . $key . uniqid() . '.' . $ext;
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
                $post_photo->photoId = $photo->id;
                $post_photo->save();
            }
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
