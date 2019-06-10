<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
	public function like($userId) {
		return $this->hasOne('App\like', 'postId', 'id')->where('userId', $userId);
	}

	public function getAllLikes() {
		return $this->hasMany('App\like', 'postId', 'id');
	}
}
