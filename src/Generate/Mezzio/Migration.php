<?php

namespace Mia\Installer\Generate\Mezzio;

use Mia\Installer\BaseFile;
use \Illuminate\Database\Capsule\Manager as DB;

class Migration extends BaseFile
{
    /**
     * Path del archivo a tener de base
     * @var string
     */
    protected $filePath = './vendor/agencycoda/mia-installer-mezzio/data/mezzio/g_migration.txt';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './database/migrations/';
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
        // Obtener las columnas de la tabla
        $columns = DB::select('DESCRIBE ' . $this->name);
        // Verificar si activar timestamp
        $hasTimestamp = false;
        // Verificar si tiene Deleted
        $hasDeleted = false;
        // Recorremos las columnas
        $properties = '';
        foreach($columns as $column){

            if($column->Field == 'id'){
                continue;
            }

            if($column->Field == 'created_at'){
                $hasTimestamp = true;
                continue;
            }

            if($column->Field == 'deleted'){
                $hasDeleted = true;
                continue;
            }

            if(stripos($column->Type, 'int') !== false){
                $properties .= '$table->integer(\''.$column->Field.'\');
';
            }else if(stripos($column->Type, 'bigint') !== false){
                $properties .= '$table->bigInteger(\''.$column->Field.'\');
';
            }else if(stripos($column->Type, 'varchar') !== false){
                $properties .= '$table->string(\''.$column->Field.'\');
';
            }else if(stripos($column->Type, 'text') !== false){
                $properties .= '$table->text(\''.$column->Field.'\');
';
            }else if(stripos($column->Type, 'decimal') !== false){
                $properties .= '$table->decimal(\''.$column->Field.'\', $presision = 12, $scale = 2);
';
            }else if(stripos($column->Type, 'float') !== false||stripos($column->Type, 'double') !== false){
                $properties .= '$table->double(\''.$column->Field.'\');
';
            }else if(stripos($column->Type, 'datetime') !== false){
                $properties .= '$table->dateTime(\''.$column->Field.'\');
';
            }else if(stripos($column->Type, 'date') !== false){
                $properties .= '$table->date(\''.$column->Field.'\');
';
            }

            
        }

        $this->file = str_replace('%%properties%%', $properties, $this->file);
        
        if($hasTimestamp){
            $this->file = str_replace('%%has_timestamp%%', '$table->timestamps();', $this->file);
        }else {
            $this->file = str_replace('%%has_timestamp%%', '', $this->file);
        }

        // Relations START
        $this->file = str_replace('%%relations%%', $this->processRelations(), $this->file);
        // Relations END

        if($hasDeleted){
            $this->file = str_replace('%%deleted%%', '$table->integer(\'deleted\')->unsigned()->default(0);', $this->file);
        }else {
            $this->file = str_replace('%%deleted%%', '', $this->file);
        }

        $this->file = str_replace('%%nameClass%%', $this->getCamelCase($this->name), $this->file);
        $this->file = str_replace('%%name%%', $this->name, $this->file);

        try {
            mkdir($this->savePath, 0777, true);
        } catch (\Exception $exc) { }

        $now = new \DateTime();
        $dateName = $now->format('Y_m_d') . '_' . $now->format('H') . $now->format('i') . $now->format('s');

        file_put_contents($this->savePath . $dateName . '_' . $this->name . '.php', $this->file);
    }

    protected function processRelations()
    {
        $result = '';
        $columns = DB::select("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME, REFERENCED_TABLE_SCHEMA FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '".$this->schema."' AND TABLE_NAME = '".$this->name."';");

        foreach($columns as $column){
            $result .= '$table->foreign(\''.$column->COLUMN_NAME.'\')->references(\'id\')->on(\''.$column->REFERENCED_TABLE_NAME.'\');';
        }

        return $result;
    }
}
