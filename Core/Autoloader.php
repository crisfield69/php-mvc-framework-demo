<?php

namespace Core;

class Autoloader 
{
    public static function register()
    {
        spl_autoload_register(function($className) {            
            $className = str_replace('\\', '/', $className);
            require_once $className . '.php';            
        });    
    }
}