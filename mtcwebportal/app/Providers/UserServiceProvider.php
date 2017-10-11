<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider {

    /**
     * Register UserService class with the Laravel IoC container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('UserService', function()
        {
            return new \App\Services\UserServiceProvider;
        });
    }

}