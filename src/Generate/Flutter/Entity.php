<?php

namespace Mia\Installer\Generate\Flutter;

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
    protected $filePath = './vendor/agencycoda/mia-installer-mezzio/data/flutter/entity.txt';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './data/flutter/entities/';
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
        $from = '';
        $maps = '';
        foreach($columns as $column){
            if(stripos($column->Type, 'int') === false){
                $properties .= "  String ".$this->getCamelCaseVar($column->Field).";\n";
            }else{
                $properties .= "  int ".$this->getCamelCaseVar($column->Field)." = 0;\n";
            }
            
            $from .= "    this.".$this->getCamelCaseVar($column->Field)." = data['".$column->Field."'];\n";
            $maps .= "      '".$column->Field."': ".$this->getCamelCaseVar($column->Field).",\n";
        }
        $this->file = str_replace('%%properties%%', $properties, $this->file);
        $this->file = str_replace('%%from%%', $from, $this->file);
        $this->file = str_replace('%%maps%%', $maps, $this->file);
        
        try {
            mkdir($this->savePath . '/', 0777, true);
        } catch (\Exception $exc) { }
        file_put_contents($this->savePath . $this->name . '.entity.dart', $this->file);
    }
}
