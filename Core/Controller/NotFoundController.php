<?php

namespace Core\Controller;

use Core\Controller\Controller;

class NotFoundController extends Controller 
{
    protected $viewPath = SITE_PATH . 'Core/View/';  
    protected $navigation = null;

    public function index() 
    {           
        $this->render('notfound', [
            'title'     =>  'Not found - 404',
            'styles'    =>  'notfound'
        ]);
    }
}