<?php

namespace App\Core;

use App\Core\Middlewares\BaseMiddleware;

abstract class  Controller {

    public string $layout='main';
    public string $action='';
    /**
    * @var App\Core\Middlewares\BaseMiddleWare[]
     */
    protected array $middlewares = [];

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
    public function setLayout($layout)
    {
       $this->layout=$layout;
    }

    public function render($view,$params=[])
    {
        return Application::$app->router->renderView($view,$params);
    }
    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }
}