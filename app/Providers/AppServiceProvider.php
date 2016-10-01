<?php

namespace App\Providers;

use App\Extensions\CrestProvider;
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
        $this->bootEveSocialite();
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

    private function bootEveSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'crest',
            function ($app) use ($socialite) {
                $config = $app['config']['services.crest'];
                return $socialite->buildProvider(CrestProvider::class, $config);
            }
        );
    }
}
