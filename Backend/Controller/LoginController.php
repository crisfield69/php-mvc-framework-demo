<?php

namespace Backend\Controller;

use Backend\Controller\Controller;

class LoginController extends Controller
{   

    public function getLogin()
    {
        $this->render('login', [
            'title'     => 'Administration - Login',
            'styles'    => 'admin'
        ]);
    }


    public function postLogin()
    {        
        foreach($this->httpRequest->post as $key => $value) {
            $$key = $value;
        }        
        if(
            $login === ADMIN_LOGIN &&
            $password === ADMIN_PASSWORD
        ) {            
            $_SESSION['admin-login'] = $login;
            $_SESSION['admin-password'] = $password;            
            $this->gotoUrl('admin');
        }
        else{
            $this->render('login', [
                'title'     => 'Administration - Login',
                'styles'    => 'admin',
                'message'   =>  'Identifiants incorrects'
            ]);
        }
    }


    public function getDisconnect()
    {
        unset($_SESSION['admin-login']);
        unset($_SESSION['admin-password']);        
        $this->gotoUrl('admin/login');        
    }
    
}