<?php

namespace App\Repositories;

use App\post;

class PostRepository
{
	public function getFriendsPost($friendId) {
		return post::select('users.name', 'posts.*', 'users.shot_path', 'photos.smallSource')
                    ->leftJoin('users', 'posts.posterId', '=', 'users.id')
                    ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
                    ->whereIn('users.id', $friendId)
                    ->orderBy('id', 'desc')
                    ->withCount('getAllLikes')
                    ->get();
	}

	public function getOwnPost($userId) {
		return post::select('users.name', 'posts.*', 'users.shot_path', 'photos.smallSource')
                    ->where('posterId', '=', $userId)
                    ->leftJoin('users', 'posts.posterId', '=', 'users.id')
                    ->leftJoin('photos', 'users.shot_path', '=', 'photos.path')
                    ->orderBy('id', 'desc')
                    ->withCount('getAllLikes')
                    ->get();
	}
}