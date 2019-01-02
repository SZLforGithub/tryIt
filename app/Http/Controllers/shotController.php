<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\User;
use App\photo;
use Auth;
use Image;

class shotController extends Controller
{
    public function uploadShot(Request $request)
    {
    	if($request->isMethod('post')){
    		$shot = $request->file('shot');
    		if($shot->isValid()){
    			//獲取Shot相關資訊
    			$originalName = $shot->getClientOriginalName();
    			$ext = $shot->getClientOriginalExtension();
    			$realPath = $shot->getRealPath();
    			$type = $shot->getClientMimeType();

    			//上傳
    			$filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' .$ext;
    			$path = Storage::putfileAs('public', $shot, $filename);
    			//dd(Storage::url($path));

    			$whoYouAre = Auth::user()->name;
    			//存入資料庫
    			$photo = new photo;
    			$photo->path = Storage::url($path);
    			$photo->user = $whoYouAre;
    			$photo->save();
    			//存入user的shot_path欄位
    			$user = User::where('name', '=', $whoYouAre)->first();
    			$user->shot_path = Storage::url($path);
    			$user->save();
    		}
    	}
    	return Redirect('profile');
    }

    public function editShot(Request $request)
    {
    	$x1 = (int)$request->x1;
    	$y1 = (int)$request->y1;
    	$w = (int)$request->w;
    	$h = (int)$request->h;

    	// get url of user's shot
    	$whoYouAre = Auth::user()->name;
    	$user = User::where('name', '=', $whoYouAre)->first();

    	// 去掉url開頭的斜線 才能順利make
    	$path = $user->shot_path;
    	$path = substr_replace($path, "", 0, 1);

    	// 建立圖片實例並編輯
    	$filename = date('Y-m-d-H-i-s') . '-' . uniqid();
    	$editPath = '/storage/'.$filename.'.jpeg';
    	$img = Image::make($path)->crop($w, $h, $x1, $y1)->save(public_path().$editPath);

    	//將變更後Shot的Path存入Photos資料表的editSource和Users資料表的shot_path
    	$photo = Photo::where('path', '=', '/'.$path)->first();
    	$photo->editSource = $editPath;
    	$photo->save();
    	$user->shot_path = $editPath;
    	$user->save();

    	return Redirect('profile');
    }
}