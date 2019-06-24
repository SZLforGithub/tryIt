<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class post extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	public function like($userId) {
		return $this->hasOne('App\like', 'postId', 'id')->where('userId', $userId);
	}

	public function getAllLikes() {
		return $this->hasMany('App\like', 'postId', 'id');
	}

	public function comment($userId) {
		return $this->hasOne('App\comment', 'postId', 'id')->where('userId', $userId);
	}

	public function getAllComments() {
		return $this->hasMany('App\comment', 'postId', 'id');
	}
}
