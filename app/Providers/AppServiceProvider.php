<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\photo;
use App\User;
use Auth;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        //將smallSource傳到app.blade home.blade stories.blade
        view()->composer('layouts.app', function($view) {
                $view->with('smallSource', $this->getVariable());
         });
        view()->composer('home', function($view) {
                $view->with('smallSource', $this->getVariable());
         });
        view()->composer('stories', function($view) {
                $view->with('smallSource', $this->getVariable());
         });
    }

    private function getVariable()
    {
        if (Auth::check()) {
            $whoYouAre = Auth::user()->id;
            $user = User::where('id', '=', $whoYouAre)->first();
            $sourcePath = $user->shot_path;
            $photo = Photo::where('path', '=', $sourcePath)->first();
            if($photo == null)
                $smallSource = null;
            else
                $smallSource = $photo->smallSource;
            return $smallSource;
        }
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local')
        {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }
}
