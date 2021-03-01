<?php

namespace Mia\Installer\Generate\Angular;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Description of Entity
 *
 * @author matiascamiletti
 */
class Entity extends \Mia\Installer\BaseFile
{
    /**
     * Path del archivo a tener de base
     * @var string
     */
    protected $filePath = './vendor/agencycoda/mia-installer-mezzio/data/angular/entity.txt';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './data/angular/entities/';
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
        
        // Obtener las columnas de la tabla
        $columns = DB::select('DESCRIBE ' . $this->name);
        // Recorremos las columnas
        $properties = '';
        foreach($columns as $column){
            if(stripos($column->Type, 'int') === false){
                $properties .= "    ".$column->Field.": number;\n";
            }else{
                $properties .= "    ".$column->Field.": string;\n";
            }
        }
        $this->file = str_replace('%%properties%%', $properties, $this->file);
        
        try {
            mkdir($this->savePath . '/', 0777, true);
        } catch (\Exception $exc) { }
        file_put_contents($this->savePath . $this->name . '.ts', $this->file);
    }
}
