<?php

namespace Mia\Installer\Added;

use \Illuminate\Database\Capsule\Manager as DB;

class Route extends \Mia\Installer\BaseFile
{
    /**
     * Path del archivo a tener de base
     * @var string
     */
    protected $filePath = './config/routes.php';
    /**
     * Path de la carpeta donde se va a guardar
     * @var string
     */
    protected $savePath = './config/routes.php';
    /**
     * Nombre de la DB
     *
     * @var string
     */
    public $name = '';
    public $nameHandler = '';
    public $paramsRequired = '';
    public $isAuth = false;
    public $isRole = -1;
    public $extraPath = '';
    public $methods = '\'GET\', \'POST\', \'OPTIONS\', \'HEAD\'';

    public function run()
    {
        $pathAfter = '';
        if($this->extraPath != ''){
            $pathAfter = '/' . $this->extraPath;
        }

        $addRoute = '    $app->route(\'/'.$this->name.'/'. $this->nameHandler . $pathAfter . '\', [';

        if($this->paramsRequired != ''){
            $addRoute .= 'new \Mia\Core\Request\MiaVerifyParamHandler(array('.$this->paramsRequired.')), ';
        }

        if($this->isAuth){
            $addRoute .= '\Mia\Auth\Handler\AuthHandler::class, ';
        }

        if($this->isRole > -1){
            $addRoute .= 'new Mobileia\Expressive\Auth\Middleware\MiaRoleAuthMiddleware([\Mobileia\Expressive\Auth\Model\MIAUser::ROLE_ADMIN]), ';
        }

        $addRoute .= 'App\Handler\\'.$this->getCamelCase($this->name).'\\' . $this->getCamelCase($this->nameHandler) . 'Handler::class], ['.$this->methods.'], \'' . $this->name .'.'. $this->nameHandler .'\');';

        $addRoute .= '
};';

        $this->file = str_replace('};', $addRoute, $this->file);
        file_put_contents($this->savePath, $this->file);
    }
}
