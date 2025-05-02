<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only force HTTPS for ngrok URLs
        if (str_contains(request()->getHost(), 'ngrok-free.app')) {
            URL::forceScheme('https');
            $this->app['url']->forceRootUrl(request()->getScheme() . '://' . request()->getHost());
            
            // Configure trusted proxies for ngrok
            Request::setTrustedProxies(
                ['127.0.0.1', request()->server->get('REMOTE_ADDR')],
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
            );
        } else {
            // For local development, use HTTP and the local domain
            URL::forceScheme('http');
            $this->app['url']->forceRootUrl('http://websecservice.localhost.com');
        }
    }
}
