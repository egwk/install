<?php

namespace Egwk\Install;

use Illuminate\Support\ServiceProvider;

class InstallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		if ($this->app->runningInConsole()) {
			$this->commands([
				DownloadCommand::class,
				InstallCommand::class,
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
			__DIR__ . '../config/install.php', 'install'
		);
    }
}
