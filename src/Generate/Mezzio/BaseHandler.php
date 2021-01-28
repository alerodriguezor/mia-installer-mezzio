<?php

namespace Mia\Installer\Generate\Mezzio;

use Mia\Installer\Added\Route;
use Mia\Installer\BaseFile;

abstract class BaseHandler extends BaseFile
{
    /**
     * Nombre de la tabla en la DB
     *
     * @var string
     */
    public $name = '';

    public function addRoute($nameHandler, $withParams = '', $isAuth = false)
    {
        $route = new Route();
        $route->name = $this->name;
        $route->nameHandler = $nameHandler;
        $route->paramsRequired = $withParams;
        $route->isAuth = $isAuth;
        $route->run();
    }
}
