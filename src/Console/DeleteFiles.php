<?php

namespace Rocketti\DependecyPattern\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteFiles extends Command
{
    protected $signature = 'dp:delete {class_name}';

    protected $description = 'Delte depedency pattern files';

    public function handle()
    {
        $class_name = $this->argument('class_name');
        $folderName = (env('APP_ENV') == 'testing') ? './tests/app/' . env('DEPENDENCY_FOLDER') . "/" : 'app/' . env('DEPENDENCY_FOLDER') . "/";

        $modelFilename = $folderName . "Models/" . ucfirst($class_name) . ".php";
        if (File::exists($modelFilename)) {
            File::delete($modelFilename);
            $this->info("Deleted " . $modelFilename);
        }

        $repositoryFilename = $folderName . "Repositories/" . ucfirst($class_name) . "Repository.php";
        if (File::exists($repositoryFilename)) {
            File::delete($repositoryFilename);
            $this->info("Deleted " . $repositoryFilename);
        }

        $serviceFilename = $folderName . "Services/" . ucfirst($class_name) . "Service.php";
        if (File::exists($serviceFilename)) {
            File::delete($serviceFilename);
            $this->info("Deleted " . $serviceFilename);
        }

        $factoryFilename = "database/factories/" . ucfirst($class_name) . "Factory.php";
        if (File::exists($factoryFilename)) {
            File::delete($factoryFilename);
            $this->info("Deleted " . $factoryFilename);
        }

        return 0;
    }
}
