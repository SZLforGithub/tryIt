<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;
use App\Post;
use App\photo;
use App\post_photo;
use Auth;
use Storage;
use DB;
use Mail;

use Illuminate\Support\Facades\Redis;

use App\Repositories\PostRepository;
use App\Repositories\PhotoRepository;

use App\Mail\MailTest;

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
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	//Mail::to('i0989872540@gmail.com')->send(new MailTest());
    	$friendId = array();
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

        // 存入posts資料表
        $post = new post;
        $post->posterId = Auth::user()->id;
        $content = nl2br($request['content']);
        $post->content = $content;
        $post->save();

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

        return Redirect()->back();
    }
}
