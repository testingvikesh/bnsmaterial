<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if ($this->app->runningInConsole()) {
            return;
        }

        $this->app->booted(function () {
            $request = request();
            $base = rtrim(str_replace('\\', '/', (string) $request->getBasePath()), '/');
            URL::forceRootUrl($request->getSchemeAndHttpHost().$base);
        });
    }
}
