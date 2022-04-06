<?php

namespace Rocketti\DependecyPattern\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDependencyPatternFiles extends Command
{
    protected $signature = 'dp:create';

    protected $description = 'Create depedency pattern files';

    public function boot()
{
    // Register the command if we are using the application via the CLI
    if ($this->app->runningInConsole()) {
        $this->commands([
            CreateDependencyPatternFiles::class,
        ]);
    }
}

    public function handle()
    {
        $this->line('teste dependencia');
    }
}