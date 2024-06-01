<?php

namespace Rocketti\DependecyPattern\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DeleteFiles extends Command
{
    protected $signature = 'dp:delete {class_name}';

    protected $description = 'Delte depedency pattern files';

    public function handle()
    {
        $class_name = $this->argument('class_name');
        $folderName = (env('APP_ENV') == 'testing') ? './tests/app/' . env('DEPENDENCY_FOLDER') . "/" : 'app/' . env('DEPENDENCY_FOLDER') . "/";

        if(File::exists($folderName . "Models/" . ucfirst($class_name) . ".php"))
        {
            $this->info("file exists: ".$folderName . "Models/" . ucfirst($class_name) . ".php");
        }

        if(File::exists($folderName . "Repositories/" . ucfirst($class_name) . "Repository.php"))
        {
            $this->info("file exists: ".$folderName . "Repositories/" . ucfirst($class_name) . "Repository.php");
        }

        if(File::exists($folderName . "Services/" . ucfirst($class_name) . "Service.php"))
        {
            $this->info("file exists: ".$folderName . "Services/" . ucfirst($class_name) . "Service.php");
        }

        return 0;
    }
}
