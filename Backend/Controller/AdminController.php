<?php

namespace Backend\Controller;

use Backend\Controller\Controller;

class AdminController extends Controller
{   
    public function getHome()
    {
        $this->render('admin', [
            'title'         => 'Administration',
            'styles'        => 'admin',
            
        ]);
    }
    
}