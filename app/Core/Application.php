<?php

namespace App\Core;

use App\Core\Application as CoreApplication;

class Application {
    public static string $ROOT_DIR;
    public static Application $app;
    public Request $request;
    public Router $router;
    public Response $response;
    public ?Controller $controller = null;
    public Database $database;
    public string $layout='main';
    public function __construct($rootPath, array $config)
    {
       self::$app=$this;
       self::$ROOT_DIR=$rootPath;
       $this->request=new Request();
       $this->response=new Response();
       $this->router=new Router($this->request, $this->response);

       $this->database = new Database($config['db']);
    }
    public function run()
    {
        try {
            $this->router->resolve();
        } catch (\Exception $exception){
            $this->response->setStatusCode($exception->getCode());
            $this->router->renderView('_error', [
                'exception' => $exception
            ]);
        }
    }
    public function getController():Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller)
    {
        $this->controller=$controller;
    }
}