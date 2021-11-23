<?php

namespace App\Providers;

use App\Repositories\Contracts\PostRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Implementations\PostRepository;
use App\Repositories\Implementations\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostRepositoryContract::class, PostRepository::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
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
