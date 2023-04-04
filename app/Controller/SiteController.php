<?php

namespace App\Controller;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;

class SiteController extends Controller
{
    public function productList(){
        return $this->render('productList', []);
    }
    public function addProduct(){
        return $this->render('addProduct', []);
    }
    public function contact(){
        return Application::$app->router->renderView('contact');
    }
    public function handleContact(Request $request){
        $body=$request->getBody();
        echo "<pre>".var_dump($body)."</pre>";
        return 'Handling submitted data';
    }
}