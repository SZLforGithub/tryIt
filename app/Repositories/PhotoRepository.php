<?php

namespace App\Repositories;

use App\post_photo;

class PhotoRepository
{
	public function getPhotosForPost() {
		return post_photo::select('post_photos.postId', 'post_photos.photoId', 'photos.path')
                    ->leftJoin('photos', 'post_photos.photoId', '=', 'photos.id')
                    ->get();
	}
}