<?php

namespace Mia\Installer\Generate\Mezzio;

use \Illuminate\Database\Capsule\Manager as DB;
use Mia\Installer\BaseFile;

class AbmHandler extends BaseFile
{
    /**
     * Nombre de la DB
     *
     * @var string
     */
    public $name = '';
    /**
     * Nombre del Schema de la base de datos
     * @var string
     */
    public $schema = '';

    public function run()
    {
        $model = new Model();
        $model->name = $this->name;
        $model->schema = $this->schema;
        $model->run();

        $model = new Migration();
        $model->name = $this->name;
        $model->run();

        $model = new Repository();
        $model->name = $this->name;
        $model->run();

        $model = new FetchHandler();
        $model->name = $this->name;
        $model->run();

        $model = new ListHandler();
        $model->name = $this->name;
        $model->run();

        $model = new RemoveHandler();
        $model->name = $this->name;
        $model->run();

        $model = new SaveHandler();
        $model->name = $this->name;
        $model->run();
    }

    protected function openFile()
    {
        
    }
}
