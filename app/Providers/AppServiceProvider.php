<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Post;
use App\Category;
use App\Comment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('pages._sidebar', function($view){
            $view->with('popularPost', Post::orderBy('views', 'desc')->take(3)->get());
            $view->with('featuredPost', Post::orderBy('date', 'desc')->take(4)->get());
            $view->with('recentPost', Post::orderBy('date', 'desc')->take(4)->get());
            $view->with('categories', Category::all());
        });

        view()->composer('admin._sidebar', function($view){
            $view->with('getNewComments', Comment::where('status', 0)->count());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
