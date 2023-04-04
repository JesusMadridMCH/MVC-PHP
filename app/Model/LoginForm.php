<?php

namespace App\Model;

use App\Core\Application;
use App\Core\Model;

class LoginForm extends Model
{
    public string $email='';
    public string $password='';
    public function rules()
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Your Email',
            'password' => 'Password',
        ];
    }
    public function login():bool
    {
        $user = new User();
        $findUser=$user->findOne(['email' => $this->email]);
        if(!$findUser){
            $this->addError('email', 'User does not exists with this email');
            return false;
        }
        if(!password_verify($this->password, $findUser->password)){
            $this->addError('password', 'Password is incorrect');
            return false;
        }
        return Application::$app->login($findUser);
    }
}