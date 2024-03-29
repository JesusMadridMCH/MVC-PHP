<?php

namespace App\Core\Middlewares;

use App\Core\Application;
use App\Core\Exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];
    public function __construct(array $actions=[])
    {
        $this->actions=array_merge($this->actions, $actions);
    }

    public function execute()
    {

    }
}