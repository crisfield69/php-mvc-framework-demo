<?php

namespace Backend\Controller;

use Backend\Controller\Controller;

class ApiController extends Controller
{   
    public function getHome()
    {
        $this->render('api', [
            'title'         =>  'Administration - Imports API',
            'styles'        =>  'admin'            
        ]);
    }
    
}