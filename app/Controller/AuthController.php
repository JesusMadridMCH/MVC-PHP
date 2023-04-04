<?php

namespace App\Controller;
use App\Core\Application;
use App\Core\Controller;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Model\LoginForm;
use App\Model\User;

class AuthController extends Controller{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile','home', 'contact', 'login', 'register']));
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if($request->isPost()){
            $loginForm->loadData($request->getBody());
            if($loginForm->validate() && $loginForm->login()){
                $response->redirect("/onlineStore");
//                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
        'model' => $loginForm
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/onlineStore');
    }

    public function register(Request $request)
    {
        $user = new User();
        if($request->isPost())
        {
            $user->loadData($request->getBody());
            if($user->validate() && $user->save()){
                Application::$app->session->setFlash('success', 'Register was successful');
                Application::$app->response->redirect('/onlineStore');
            }
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function profile()
    {
        Application::$app->router->title
        return $this->render('profile');
    }
}