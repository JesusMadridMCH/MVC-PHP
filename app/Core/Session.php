<?php

namespace App\Core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMessages=$_SESSION[self::FLASH_KEY]?? [];
        foreach ($flashMessages as $key=>&$flashMessage){
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY]=$flashMessages;
    }

    public function setFlash($key, $message)
    {
        echo "KEY: $key - message $message <br>";
        $_SESSION[self::FLASH_KEY][$key]=[
            'remove' => false,
            'value' => $message,
        ];
        echo "<pre>".print_r($_SESSION[self::FLASH_KEY],1)."</pre>";
    }
    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function get($key){
        return $_SESSION[$key]?? false;
    }
    public function set($key, $value){
        $_SESSION[$key]=$value;
    }

    public function  remove($key){
        unset($_SESSION[$key]);
    }
    public function __destruct()
    {
        $flashMessages=$_SESSION[self::FLASH_KEY]?? [];
        foreach ($flashMessages as $key=> &$flashMessage) {
            if($flashMessage['remove']){
                unset($flashMessage[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY]=$flashMessages;
//        echo "<pre>".print_r($_SESSION[self::FLASH_KEY],1)."</pre>";
    }
}