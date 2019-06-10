<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use App\post;
use App\photo;
use App\post_photo;
use App\friend_relation;
use Auth;
use DB;
use Exception;

use App\Repositories\PostRepository;
use App\Repositories\PhotoRepository;


class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($whoYouAre)
    {
		$user = User::where('name', '=', $whoYouAre)->first();
        $areYouFriend = $this->areYouFriend($user->id);
        $anyAddFriend = DB::table('friend_relations')
                    ->select('friend_relations.userId1', 'users.name', 'users.shot_path', 'photos.smallSource')
                    ->where('friend_relations.userId2', '=', Auth::user()->id)
                    ->where('friend_relations.areFriends', 'add')
                    ->leftJoin('users', 'users.id', '=', 'friend_relations.userId1')
                    ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
                    ->get();
    	
        $photoPath = Photo::where('path', '=', $user->shot_path)->first();

        $PostRepository = new PostRepository();
		$posts = $PostRepository->getOwnPost($user->id);

		$PhotoRepository = new PhotoRepository();
		$photos = $PhotoRepository->getPhotosForPost();

    	return view('stories', [
            'posts' => $posts,
            'photos' => $photos,
            'photoPath' => $photoPath,
            'user' => $user,
            'areYouFriend' => $areYouFriend,
            'anyAddFriend' => $anyAddFriend,
        ]);
    }

    public function areYouFriend($id) {
        $friend_relation = DB::table('friend_relations')
                    ->select('friend_relations.userId1', 'friend_relations.userId2', 'friend_relations.areFriends')
                    ->where('userId1', '=', Auth::user()->id)
                    ->orwhere("userId2", '=', Auth::user()->id)
                    ->get();

        if ($friend_relation->isEmpty())
            return 'N';

        foreach ($friend_relation as $userId) {
            if ($id == $userId->userId2 || ($id == $userId->userId1 && $userId->userId2 == Auth::user()->id) ) {
                if ($userId->areFriends == 'add') {
                    return 'add';
                }
                else if ($userId->areFriends == 'Y') {
                    return 'Y';
                }
            }
            else
                continue;
        }
        return 'N';
    }
}
