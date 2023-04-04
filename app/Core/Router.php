<?php

namespace App\Core;

use App\Core\Exception\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    
    public function __construct(Request $request, Response $response)
    {
        $this->request=$request;
        $this->response=$response;
    }

    public function get($path,$callback)
    {
        $this->routes['get'][$path]=$callback;
    }

    public function post($path,$callback)
    {
        $this->routes['post'][$path]=$callback;
    }

    public function resolve()
    {
        $params=$this->request->getParams();
        $path=!empty($params) && count($params)>1? "/$params[1]": $this->request->getPath();
        $method=$this->request->getMethod();
        $callback=$this->routes[$method][$path] ?? false;
        if($callback === false){
//            throw new NotFoundException();
            exit;
        }
        if(is_string($callback))
        {
            return $this->renderView($callback);
        }
        if(is_array($callback))
        {
            /** @var  \App\Core\Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller=$controller;
            $controller->action = $callback[1];
            $callback[0]=$controller;
            foreach ($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }
        echo call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params=[])
    {
        $layoutContent=$this->layoutContent();
        $viewContent=$this->renderOnlyView($view, $params);
        echo str_replace("{{content}}",$viewContent,$layoutContent);
    }

    public function renderContent($viewContent)
    {
        $layoutContent=$this->layoutContent();
        echo str_replace("{{content}}",$viewContent,$layoutContent);
    }

    protected function layoutContent()
    {
        $layout=Application::$app->controller? Application::$app->controller->layout : Application::$app->layout;
        ob_start();
        include_once Application::$ROOT_DIR."/View/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        /* This is for fill the variables in the next include php page */
        foreach($params as $key => $value)
        {
            $$key=$value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/View/$view.php";
        return ob_get_clean();
    }
}