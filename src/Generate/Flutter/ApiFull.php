<?php

namespace Mia\Installer\Generate\Flutter;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Description of Entity
 *
 * @author matiascamiletti
 */
class ApiFull extends \Mia\Installer\BaseFile
{
    /**
     * Nombre de la DB
     *
     * @var string
     */
    public $name = '';

    public function run()
    {
        $model = new Api();
        $model->name = $this->name;
        $model->run();

        $model = new ApiChopper();
        $model->name = $this->name;
        $model->run();
    }

    protected function openFile()
    {
        
    }
}
