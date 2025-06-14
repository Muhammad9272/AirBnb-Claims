<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Models\SocialSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\CentralLogics\CartLogics;
use App\CentralLogics\Helpers;
use Socialite;
use SocialiteProviders\Discord\Provider as DiscordProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('*', function($settings) {
            $settings->with('gs', GeneralSetting::find(1));
            $settings->with('sociallinks', SocialSetting::find(1));
        });

        // Share Cart and Helper classes with all views
        View::composer('*', function ($view) {
            $view->with('CartLogics', CartLogics::class);
            $view->with('Helpers', Helpers::class);
        });

        Socialite::extend('discord', function ($app) {
            $config = $app['config']['services.discord'];
            $request = $app['request'];  // Get the correct Request instance
            $encrypter = $app['encrypter']; // Encrypter for security

            return new DiscordProvider(
                $request, // Pass the Request instance here
                $encrypter,
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }
}
