<?php

namespace Rocketti\DependecyPattern\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDependencyPatternFiles extends Command
{
    protected $signature = 'dp:create';

    protected $description = 'Create depedency pattern files';

    public function handle()
    {
        $this->line('teste dependencia');
    }
}