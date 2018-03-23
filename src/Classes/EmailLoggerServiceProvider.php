<?php

namespace Salvakexx\EmailLogger;

use Illuminate\Support\ServiceProvider;

class EmailLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->publishes([
//            __DIR__.'/views' => base_path('resources/views/laraveldaily/timezones'),
//        ]);

        $this->loadViewsFrom(__DIR__.'/../views', 'email-logger');

        $this->publishes([
            __DIR__.'/../config/email-logger.php' => config_path('email-logger.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('email-logger', 'Salvakexx\EmailLogger\EmailLogger' );
    }
}
