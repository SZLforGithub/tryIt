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
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function uploadShot(Request $request)
    {
    	if($request->isMethod('post')){
    		$shot = $request->file('shot');
    		if($shot->isValid()){
    			// 獲取Shot相關資訊
    			$originalName = $shot->getClientOriginalName();
    			$ext = $shot->getClientOriginalExtension();
    			$realPath = $shot->getRealPath();
    			$type = $shot->getClientMimeType();

    			// 上傳
    			$filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' .$ext;
    			$path = Storage::putfileAs('public', $shot, $filename);
    			//dd(Storage::url($path));

    			$sourcePath = Storage::url($path);
    			$whoYouAre = Auth::user()->name;
    			// 存入資料庫
    			$photo = new photo;
    			$photo->path = $sourcePath;
    			$photo->user = $whoYouAre;
    			$photo->save();
    			// 存入user的shot_path欄位
    			$user = User::where('name', '=', $whoYouAre)->first();
    			$user->shot_path = $sourcePath;
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

    	// XXX Maybe this part can do smarter
    	// 去掉url開頭的斜線 才能順利make
    	$sourcePath = $user->shot_path;
    	$path = substr_replace($sourcePath, "", 0, 1);

    	// 建立圖片實例並編輯
    	$filename = date('Y-m-d-H-i-s') . '-' . uniqid();
    	$smallFilename = 's' . date('Y-m-d-H-i-s') . '-' . uniqid();
    	$editPath = 'storage/'.$filename.'.jpeg';
    	$smallEditPath = '/storage/'.$smallFilename.'.jpeg';
    	$img = Image::make($path)->crop($w, $h, $x1, $y1)->save(public_path().'/'.$editPath);
    	$img = Image::make($editPath)->resize(50, 50)->save(public_path().$smallEditPath);

    	// 將裁切後Shot的Path存入Photos資料表的editSource和smallSource
    	$photo = Photo::where('path', '=', $sourcePath)->first();
    	$photo->editSource = $editPath;
    	$photo->smallSource = $smallEditPath;
    	$photo->save();

    	return Redirect('profile');
    }
}