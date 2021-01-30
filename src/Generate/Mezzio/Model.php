<?php

namespace Mia\Installer\Generate\Mezzio;

use Mia\Installer\BaseFile;
use \Illuminate\Database\Capsule\Manager as DB;
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

        // Obtener las columnas de la tabla
        $columns = DB::select('DESCRIBE ' . $this->name);
        // Recorremos las columnas
        $swagger = '';
        foreach($columns as $column){
            if($column->Field == 'id'){
                continue;
            }
            $swagger .= ' * @OA\Property(
 *  property="'.$column->Field.'",
 *  type="'.$column->Type.'",
 *  description=""
 * )
 ';
        }
        $this->file = str_replace('%%swagger%%', $swagger, $this->file);
        
        try {
            mkdir($this->savePath, 0777, true);
        } catch (\Exception $exc) { }
        file_put_contents($this->savePath . $this->getCamelCase($this->name) . '.php', $this->file);
    }
}
