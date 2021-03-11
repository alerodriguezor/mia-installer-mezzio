<?php

namespace Mia\Installer\Generate\Mezzio;

class FetchHandler extends BaseHandler
{
    /**
     * Path del archivo a tener de base
     * @var string
     */
    protected $filePath = './vendor/agencycoda/mia-installer-mezzio/data/mezzio/g_handler_fetch.txt';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './src/App/src/Handler/';

    public function run()
    {
        $this->file = str_replace('%%nameClass%%', $this->getCamelCase($this->name), $this->file);
        $this->file = str_replace('%%name%%', $this->name, $this->file);
        
        try {
            mkdir($this->savePath . '/' . $this->getCamelCase($this->name), 0777, true);
        } catch (\Exception $exc) { }
        file_put_contents($this->savePath . '/' . $this->getCamelCase($this->name) . '/FetchHandler.php', $this->file);

        // Agregamos route
        $this->addRoute('fetch', '', true, '{id}', '\'GET\', \'OPTIONS\', \'HEAD\'');
    }
}
