<?php

namespace Mia\Installer\Generate\Mezzio;

use Mia\Installer\BaseFile;

class Model extends BaseFile
{
    /**
     * Path del archivo a tener de base
     * @var string
     */
    protected $filePath = './vendor/agencycoda/mia-installer-mezzio/data/mezzio/g_model.txt';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './src/App/src/Model/';
    /**
     * Nombre de la DB
     *
     * @var string
     */
    public $name = '';

    public function run()
    {
        $this->file = str_replace('%%nameClass%%', $this->getCamelCase($this->name), $this->file);
        $this->file = str_replace('%%name%%', $this->name, $this->file);
        
        try {
            mkdir($this->savePath, 0777, true);
        } catch (\Exception $exc) { }
        file_put_contents($this->savePath . $this->getCamelCase($this->name) . '.php', $this->file);
    }
}
