<?php

namespace Rocketti\DependecyPattern;

use Illuminate\Support\ServiceProvider;
use Rocketti\DependecyPattern\Console\CreateDependencyPatternFiles;

class DependencyPatterServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {

        if(env('APP_ENV') == 'testing'){
            $this->loadMigrationsFrom(__DIR__.'/tests/database/migrations');
        }

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateDependencyPatternFiles::class,
            ]);
        }
    }
}