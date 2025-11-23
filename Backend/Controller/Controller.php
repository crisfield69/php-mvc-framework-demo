<?php

namespace Backend\Controller;

use Core\Controller\Controller as CoreController;
use Core\HttpRequest;

class Controller extends CoreController
{    
    protected $viewPath     =   SITE_PATH . 'Backend/View/';        
    protected $navigation   =   ADMIN_NAVIGATION;
    protected $httpRequest;

    public function __construct()
    {
        session_start();
        $this->httpRequest = new HttpRequest();
        if(!$this->isConnected() && $this->httpRequest->currentUrl !== 'admin/login') {
            $this->gotoUrl('admin/login');
        }        
    }

    
    protected function isConnected()
    {        
        if(
            isset($_SESSION) &&
            isset($_SESSION['admin-login']) &&
            isset($_SESSION['admin-password']) &&
            (                
                ($_SESSION['admin-login'] === ADMIN_LOGIN && $_SESSION['admin-password'] === ADMIN_PASSWORD)
            )
        ) {
            return true;
        }
        return false;
    }

}
