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

use Illuminate\Support\Facades\Redis;

use App\Repositories\PostRepository;
use App\Repositories\PhotoRepository;

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
    public function index() {
    	/*$values = Redis::get('string');
    	dd($values);*/
    	
    	$friend_relations = DB::table('friend_relations')
    				->select('userId1', 'userId2')
    				->where('userId1', '=', Auth::user()->id)
    				->orwhere('userId2', '=', Auth::user()->id)
    				->get();
    	foreach ($friend_relations as $key => $friend_relation) {
    		if ($friend_relation->userId1 != Auth::user()->id) {
            	$friendId[$key] = $friend_relation->userId1;
    		}
            else if ($friend_relation->userId2 != Auth::user()->id) {
            	$friendId[$key] = $friend_relation->userId2;
            }
        }

		$PostRepository = new PostRepository();
		$posts = $PostRepository->getFriendsPost($friendId);

        $PhotoRepository = new PhotoRepository();
		$photos = $PhotoRepository->getPhotosForPost();

        return view('home', ['posts' => $posts, 'photos' => $photos]);
    }

    public function create(Request $request) {
        $request = $request::all();

        // 存入posts & post_backups資料表
        $post = new post;
        $post->posterId = Auth::user()->id;
        $content = nl2br($request['content']);
        $post->content = $content;
        $post->save();

        $post_backup = new post_backup;
        $post_backup->posterId = Auth::user()->id;
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
                $whoYouAre = Auth::user()->id;
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

    public function edit(Request $request, $id) {
        $request = $request::all();
        $content = nl2br($request['content']);
        $post = post::find($id);
        $post->content = $content;
        $post->save();

        $post_backup = post_backup::find($id);
        $post_backup->content = $content;
        $post_backup->save();

        return Redirect('home');
    }
}
