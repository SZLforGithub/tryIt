<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Hash;
use Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Facebook, Github, Line etc. login and data processing
    public function redirect($provider) {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider) {
        if (request()->error == 'access_denied') {
            $error = '授權失敗，請再試一次。';
            return redirect('login')->with('error', $error);
        }

        $user = Socialite::driver($provider)->user();
        if (!$user->email) {
            $error = '你沒有提供電子郵件授權，無法登入，請至FB重新授權。';
            return redirect('login')->with('error', $error);
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    public function findOrCreateUser($user, $provider) {
        $authUser = User::where('provider_id', '=', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }

        $authUser = User::where('email', '=', $user->email)->first();
        if ($authUser) {
            $authUser->provider = $provider;
            $authUser->provider_id = $user->id;
            $authUser->save();
            return $authUser;
        }
        
        return User::create([
            'name'          =>  $user->name,
            'email'         =>  $user->email,
            'provider'      =>  $provider,
            'provider_id'   =>  $user->id,
            'password'      =>  Hash::make(uniqid()),
        ]);
    }
}
