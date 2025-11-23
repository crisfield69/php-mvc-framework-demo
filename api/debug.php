<?php

ini_set("display_errors", "1");
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function d($data)
{    
    echo '<pre>';
    print_r($data);
    echo '</pre>';        
}


function dd($data)
{    
    d($data);
    die();
}
