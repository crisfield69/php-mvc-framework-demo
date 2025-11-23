<?php

namespace Core\Routing;

class RouterException extends \Exception 
{
    public function __construct($message)
    {
        echo $message;
        die();        
    }

}