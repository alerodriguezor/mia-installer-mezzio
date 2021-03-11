<?php

namespace Mia\Installer\Generate\Mezzio;

use \Illuminate\Database\Capsule\Manager as DB;

class SaveHandler extends BaseHandler
{
    /**
     * Path del archivo a tener de base
     * @var string
     */
    protected $filePath = './vendor/agencycoda/mia-installer-mezzio/data/mezzio/g_handler_save.txt';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './src/App/src/Handler/';

    public function run()
    {
        $this->file = str_replace('%%nameClass%%', $this->getCamelCase($this->name), $this->file);
        $this->file = str_replace('%%name%%', $this->name, $this->file);

        // Obtener las columnas de la tabla
        $columns = DB::select('DESCRIBE ' . $this->name);
        // Recorremos las columnas
        $properties = '';
        foreach($columns as $column){
            if($column->Field == 'id'){
                continue;
            }
            if(stripos($column->Type, 'int') !== false){
                $properties .= '        $item->'.$column->Field.' = intval($this->getParam($request, \''.$column->Field.'\', \'\'));
                ';
            }else{
                $properties .= '        $item->'.$column->Field.' = $this->getParam($request, \''.$column->Field.'\', \'\');
                ';
            }
        }
        $this->file = str_replace('%%properties%%', $properties, $this->file);
        
        try {
            mkdir($this->savePath . '/' . $this->getCamelCase($this->name), 0777, true);
        } catch (\Exception $exc) { }
        file_put_contents($this->savePath . '/' . $this->getCamelCase($this->name) . '/SaveHandler.php', $this->file);

        // Agregamos route
        $this->addRoute('save', '', true, '', '\'POST\', \'OPTIONS\', \'HEAD\'');
    }
}
