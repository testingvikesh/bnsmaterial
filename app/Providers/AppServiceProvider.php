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

            $this->configureAssetUrlForProjectRootHosting();
        });
    }

    /**
     * When cPanel points the document root at the project folder (not /public),
     * Laravel's asset() helper must prefix URLs with /public so CSS/JS load correctly.
     */
    private function configureAssetUrlForProjectRootHosting(): void
    {
        if (config('app.asset_url')) {
            return;
        }

        $documentRoot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
        $publicPath = realpath(public_path());

        if (! $documentRoot || ! $publicPath || $documentRoot === $publicPath) {
            return;
        }

        if (! str_starts_with($publicPath, $documentRoot.DIRECTORY_SEPARATOR)) {
            return;
        }

        $publicSegment = trim(str_replace('\\', '/', substr($publicPath, strlen($documentRoot))), '/');

        if ($publicSegment === '') {
            return;
        }

        config([
            'app.asset_url' => rtrim((string) config('app.url'), '/').'/'.$publicSegment,
        ]);
    }
}
