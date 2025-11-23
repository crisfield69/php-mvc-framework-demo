<?php

namespace Frontend\Controller;

use Backend\Model\ConferencesModel;

class TestController extends Controller 
{
   
    public function __construct()
    {
        parent::__construct();
    }

    public function getHome()
    {        
        echo phpinfo();
        
    }

    

    

}