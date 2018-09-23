<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function ($user) {
            retry(5, function() use($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 1e2);

        });

        User::updated(function ($user) {
            if ($user->isDirty('email')) {
                retry(5, function() use($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 1e2);
            }
        });

        Product::observe(ProductObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
