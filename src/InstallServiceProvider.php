<?php

namespace Egwk\Install;

use Illuminate\Support\ServiceProvider;
use Egwk\Install\Console\Commands;

class InstallServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole())
        {
            $this->commands([
                Commands\Download::class,
                Commands\Install::class,
                Commands\Import::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
                __DIR__ . '/../config/install.php', 'install'
        );
        $this->mergeConfigFrom(
                __DIR__ . '/../config/stopwords.php', 'install.stopwords'
        );
    }

}
