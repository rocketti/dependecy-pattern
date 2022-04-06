<?php

namespace Rocketti\DependecyPattern;

use Illuminate\Support\ServiceProvider;
use Rocketti\DependecyPattern\Console\CreateDependencyPatternFiles;

class BlogPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateDependencyPatternFiles::class,
            ]);
        }
    }
}