<?php

namespace App\Repositories;

use App\post;
use Auth;

class PostRepository
{
     static $row_per_page = 10;

	public function getFriendsPost($friendId) {
          array_push($friendId, Auth::user()->id);

		return post::select('users.name', 'posts.*', 'users.shot_path', 'photos.smallSource')
                    ->leftJoin('users', 'posts.posterId', '=', 'users.id')
                    ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
                    ->whereIn('users.id', $friendId)
                    ->orderBy('id', 'desc')
                    ->withCount('getAllLikes')
                    ->withCount('getAllComments')
                    ->paginate($this::$row_per_page);
	}

	public function getOwnPost($userId) {
		return post::select('users.name', 'posts.*', 'users.shot_path', 'photos.smallSource')
                    ->where('posterId', '=', $userId)
                    ->leftJoin('users', 'posts.posterId', '=', 'users.id')
                    ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
                    ->orderBy('id', 'desc')
                    ->withCount('getAllLikes')
                    ->withCount('getAllComments')
                    ->paginate($this::$row_per_page);
	}
}