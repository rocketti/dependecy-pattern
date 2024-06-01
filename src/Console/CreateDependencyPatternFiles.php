<?php

namespace Rocketti\DependecyPattern\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CreateDependencyPatternFiles extends Command
{
    const TYPE_UPDATE = 'update';
    const TYPE_RECREATE = 'recreate';
    const TYPE_CREATE = 'create';

    // protected $hidden = true;
    protected $signature = 'dp:file {class_name} {table_name} {--check} {--recreate}';

    protected $description = 'Create and update depedency pattern files';

    public function handle()
    {
        $validator = $this->validateDependencies();
        if ($validator == 1) {
            return 1;
        }

        $operation = self::TYPE_CREATE;

        if ($this->option('recreate')) {
            $operation = self::TYPE_RECREATE;
        }

        $class_name = $this->argument('class_name');
        $table_name = $this->argument('table_name');
        $class = "App\\" . env('DEPENDENCY_FOLDER');
        $folderName = (env('APP_ENV') == 'testing') ? './tests/app/' . env('DEPENDENCY_FOLDER') . "/" : 'app/' . env('DEPENDENCY_FOLDER') . "/";

        if ($this->option('check')) {
            $this->line('Checking if folder exists...');
            $this->folderExists($folderName, $class);
            $this->line('Checking if folder exists... done.');
        }

        
        $this->line('Creating model...');
        $this->modelFile($folderName, $class_name, $class, $table_name, $operation);

        $this->line('Creating repository...');
        $this->RepositoryFile($folderName, $class_name, $class, $operation);

        $this->line('Creating service...');
        $this->serviceFile($folderName, $class_name, $class, $operation);

        $this->line('Creating factory...');
        $this->factoryFile($folderName, $class_name, $class, $operation);

        return 0;
    }

    private function validateDependencies()
    {
        if (env('DEPENDENCY_FOLDER') == '' || env('DEPENDENCY_FOLDER') == null) {
            $this->error("                                                                                                                        ");
            $this->error("  Please, check the env variable -> DEPENDENCY_FOLDER                                                                   ");
            $this->error("                                                                                                                        ");
            return 1;
        }

        if ($this->argument('class_name') == null  || $this->argument('class_name') == '') {
            $this->error("                                                                                                                        ");
            $this->error("  Please, check argument class_name                                                                                     ");
            $this->error("                                                                                                                        ");
            return 1;
        }

        if ($this->argument('table_name') == null || $this->argument('table_name') == '') {
            $this->error("                                                                                                                        ");
            $this->error("  Please, check argument table_name                                                                                     ");
            $this->error("                                                                                                                        ");
            return 1;
        }

        DB::connection()->getPdo();

        if (!Schema::hasTable($this->argument('table_name'))) {
            $this->error("                                                                                                                        ");
            $this->error("  Please, check if table was created.                                                                                   ");
            $this->error("                                                                                                                        ");
            return 1;
        }

        return 0;
    }

    private function folderExists($folderName, $class)
    {
        $folders = [
            'Contracts', 'Services', 'Repositories', 'Models'
        ];

        if (!File::exists($folderName)) {
            File::makeDirectory($folderName);
            foreach ($folders as $folder) {
                File::makeDirectory($folderName . "/" . $folder);
            }
        } elseif (File::exists($folderName)) {
            foreach ($folders as $folder) {
                if (!File::exists($folderName . "/" . $folder)) {
                    File::makeDirectory($folderName . "/" . $folder);
                }
            }
        }

        if (!File::exists($folderName . "/Contracts/ServiceContract.php")) {
            $content = '<?php

namespace ' . $class . '\Contracts;
            
interface ServiceContract
{
    /**
    * @return mixed
     */
    public function renderList();

    /**
     * @param $id
     * @return mixed
     */
    public function renderEdit($id);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function buildUpdate($id, $data);

    /**
     * @param $data
     * @return mixed
     */
    public function buildInsert($data);

    /**
     * @param $id
     * @return mixed
     */
    public function buildDelete($id);
}';
            File::put($folderName . "/Contracts/ServiceContract.php", $content);
        }
    }

    private function modelFile($folderName, $class_name, $class, $table_name, $operation)
    {
        if (!File::exists($folderName . "Models/" . ucfirst($class_name) . ".php") || $operation == self::TYPE_RECREATE) {
            $columns = Schema::getColumnListing($table_name);
            $castsFinal = [];

            foreach ($columns as $key => $column) {

                if (in_array($column, ['id', 'created_at', 'updated_at'])) {
                    unset($columns[$key]);
                    continue;
                }

                $type = Schema::getColumnType($table_name, $column);
                if (in_array($type, ['date', 'timestamp'])) {
                    if ($type == 'date') {
                        $castsFinal[] = "'$column' => 'date'";
                    }
                    if ($type == 'timestamp') {
                        $castsFinal[] = "'$column' => 'datetime'";
                    }
                }
            }

            $columnsFinal = "'" . implode("','", $columns) . "'";
            $casts = implode("','", $castsFinal);

            $content = '<?php

namespace ' . $class . '\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ' . ucfirst($class_name) . ' extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "' . $table_name . '";

    protected $casts = [
        ' . $casts . '
    ];

    protected $fillable = [
        ' . $columnsFinal . '
    ];
}';
            File::put($folderName . "Models/" . ucfirst($class_name) . ".php", $content);

            if ($operation == self::TYPE_CREATE) {
                $this->info('Creating model... done.');
            }

            if ($operation == self::TYPE_RECREATE) {
                $this->info('Recreating model... done.');
            }
        } else {
            $this->warn('Model exists... skipping.');
        }
    }

    private function repositoryFile($folderName, $class_name, $class, $operation)
    {
        if (!File::exists($folderName . "Repositories/" . ucfirst($class_name) . "Repository.php") || $operation == self::TYPE_RECREATE) {

            $content = '<?php

namespace ' . $class . '\Repositories;

use ' . $class . '\Models\\' . ucfirst($class_name) . ';

class ' . ucfirst($class_name) . 'Repository
{
    /**
     * @var Role
     */
    private $model;

    /**
     * ' . ucfirst($class_name) . ' Repository constructor.
     * @param ' . ucfirst($class_name) . ' $' . lcfirst($class_name) . '
     */
    public function __construct(' . $class_name . ' $' . lcfirst($class_name) . ')
    {
        $this->model = $' . lcfirst($class_name) . ';
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->get();
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return $this->model->find($id)->fill($data)->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }
}';
            File::put($folderName . "Repositories/" . ucfirst($class_name) . "Repository.php", $content);

            if ($operation == self::TYPE_CREATE) {
                $this->info('Creating repository... done.');
            }

            if ($operation == self::TYPE_RECREATE) {
                $this->info('Recreating repository... done.');
            }
        } else {
            $this->warn('Repository exists... skipping.');
        }
    }

    private function serviceFile($folderName, $class_name, $class, $operation)
    {
        if (!File::exists($folderName . "Services/" . ucfirst($class_name) . "Service.php") || $operation == self::TYPE_RECREATE) {
            $content = '<?php

namespace ' . $class . '\Services;

use ' . $class . '\Contracts\ServiceContract;
use ' . $class . '\Repositories\\' . $class_name . 'Repository;

class ' . $class_name . 'Service implements ServiceContract
{
     /**
     * @var ' . $class_name . 'Repository
     */
    private $repository;

     /**
     * @var ' . $class_name . 'Repository
     */
    private $' . lcfirst($class_name) . 'Repository;

    /**
     * ' . $class_name . ' Service constructor.
     * @param ' . $class_name . 'Repository $' . lcfirst($class_name) . 'Repository
     */
    public function __construct(' . $class_name . 'Repository $' . lcfirst($class_name) . 'Repository)
    {
        $this->repository = $' . lcfirst($class_name) . 'Repository;
    }

    /**
     * @return mixed
     */
    public function renderList()
    {
        return $this->repository->getAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function renderEdit($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function buildUpdate($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function buildInsert($data)
    {
        return $this->repository->create($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function buildDelete($id)
    {
        return $this->repository->delete($id);
    }
}';
            File::put($folderName . "Services/" . ucfirst($class_name) . "Service.php", $content);
            if ($operation == self::TYPE_CREATE) {
                $this->info('Creating service... done.');
            }

            if ($operation == self::TYPE_RECREATE) {
                $this->info('Recreating service... done.');
            }
        } else {
            $this->warn('Service exists... skipping.');
        }
    }

    private function factoryFile($class_name, $class, $operation)
    {
        if (!File::exists("database/factories/" . ucfirst($class_name) . "Factory.php") || $operation == self::TYPE_RECREATE) {
            $content = '<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\\'.ucfirst($class_name).'>
 */
class '.ucfirst($class_name).'Factory extends Factory
{
    /**
     * Define the model\'s default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }
}';
            File::put("database/factories/" . ucfirst($class_name) . "Factory.php", $content);
            if ($operation == self::TYPE_CREATE) {
                $this->info('Creating factory... done.');
            }

            if ($operation == self::TYPE_RECREATE) {
                $this->info('Recreating factory... done.');
            }
        } else {
            $this->warn('Factory exists... skipping.');
        }
    }
}
