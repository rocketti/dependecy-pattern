<?php

namespace Rocketti\DependecyPattern\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CreateDependencyPatternFiles extends Command
{
    // protected $hidden = true;
    protected $signature = 'dp:create {class_name} {table_name} {--check}';

    protected $description = 'Create depedency pattern files';

    public function handle()
    {
        $validator = $this->validateDependencies();
        if($validator == 1) {
            return 1;
        }

        $class_name = $this->argument('class_name');
        $table_name = $this->argument('table_name');
        $class = "App\\".env('DEPENDENCY_FOLDER');
        $folderName = (env('APP_ENV') == 'testing')?'./tests/app/'.env('DEPENDENCY_FOLDER')."/":'app/'.env('DEPENDENCY_FOLDER')."/";

        if($this->option('check'))
        {
            $this->line('Checking if folder exists...');
            $this->folderExists($folderName,$class);
            $this->line('Checking if folder exists... done.');
        }

        $this->line('Creating model...');
        $this->createModel($folderName,$class_name,$class,$table_name);
        $this->line('Creating model... done.');

        $this->line('Creating repository...');
        $this->createRepository($folderName,$class_name,$class);
        $this->line('Creating repository... done.');

        $this->line('Creating service...');
        $this->createService($folderName,$class_name,$class);
        $this->line('Creating service... done.');
        return 0;
    }

    private function validateDependencies()
    {
        if(env('DEPENDENCY_FOLDER') == '' || env('DEPENDENCY_FOLDER') == null) {
            $this->error("                                                                                                                        ");
            $this->error('  Please, check the env variable -> DEPENDENCY_FOLDER                                                                   ');
            $this->error("                                                                                                                        ");
            return 1;
        }

        if($this->argument('class_name') == null  || $this->argument('class_name') == '') {
            $this->error("                                                                                                                        ");
            $this->error('  Please, check argument class_name                                                                                     ');
            $this->error("                                                                                                                        ");
            return 1;
        }
        
        if($this->argument('table_name') == null ||$this->argument('table_name') == '') {
            $this->error("                                                                                                                        ");
            $this->error('  Please, check argument table_name                                                                                     ');
            $this->error("                                                                                                                        ");
            return 1;
        }

        DB::connection()->getPdo();
        
        return 0;
    }

    private function folderExists($folderName,$class)
    {
        $folders = [
            'Contracts','Services','Repositories','Models'
        ];
        if(!File::exists($folderName))
        {
            File::makeDirectory($folderName);
            foreach($folders as $folder)
            {
                File::makeDirectory($folderName."/".$folder);
            }
        } elseif (File::exists($folderName)) {
            foreach($folders as $folder)
            {
                if(!File::exists($folderName."/".$folder))
                {
                    File::makeDirectory($folderName."/".$folder);
                }
            }
        }

        // check contract file:

        if(!File::exists($folderName."/Contracts/ServiceContract.php"))
        {
            $content = '<?php

namespace '.$class.'\Contracts;
            
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
            File::put($folderName."/Contracts/ServiceContract.php",$content);
        }
        
    }

    private function createModel($folderName,$class_name,$class,$table_name)
    {
        if(!File::exists($folderName."Models/".ucfirst($class_name).".php"))
        {
            $columns = Schema::getColumnListing($table_name);
            $columns = "'".implode("','", $columns)."'";
            $content = '<?php

namespace '.$class.'\Models;

use Illuminate\Database\Eloquent\Model;

class '.ucfirst($class_name).' extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "'.$table_name.'";

    protected $fillable = [
        '.$columns.'
    ];
}';
        File::put($folderName."Models/".ucfirst($class_name).".php",$content);
        }
    }

    private function createRepository($folderName,$class_name,$class)
    {
        if(!File::exists($folderName."Repositories/".ucfirst($class_name)."Repository.php"))
        {

        $content = '<?php

namespace '.$class.'\Repositories;

use '.$class.'\Models\\'.ucfirst($class_name).';

class '.ucfirst($class_name).'Repository
{
    /**
     * @var Role
     */
    private $model;

    /**
     * '.ucfirst($class_name).' Repository constructor.
     * @param '.ucfirst($class_name).' $'.lcfirst($class_name).'
     */
    public function __construct('.$class_name.' $'.lcfirst($class_name).')
    {
        $this->model = $'.lcfirst($class_name).';
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
            File::put($folderName."Repositories/".ucfirst($class_name)."Repository.php",$content);
        }  
    }

    private function createService($folderName,$class_name,$class)
    {
        if(!File::exists($folderName."Services/".ucfirst($class_name)."Service.php"))
        {
            $content = '<?php

namespace '.$class.'\Services;

use '.$class.'\Contracts\ServiceContract;
use '.$class.'\Repositories\\'.$class_name.'Repository;

class '.$class_name.'Service implements ServiceContract
{
     /**
     * @var '.$class_name.'Repository
     */
    private $repository;

     /**
     * @var '.$class_name.'Repository
     */
    private $'.lcfirst($class_name).'Repository;

    /**
     * '.$class_name.' Service constructor.
     * @param '.$class_name.'Repository $'.lcfirst($class_name).'Repository
     */
    public function __construct('.$class_name.'Repository $'.lcfirst($class_name).'Repository)
    {
        $this->repository = $'.lcfirst($class_name).'Repository;
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
            File::put($folderName."Services/".ucfirst($class_name)."Service.php",$content);
        }
    }
}