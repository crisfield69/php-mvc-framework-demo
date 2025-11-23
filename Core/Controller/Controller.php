<?php

namespace Core\Controller;

class Controller
{
    protected $viewPath; 
    protected $navigation;

    protected function render($view, $arrayarams = [])
    {
        $navigation = $this->getNavigation();
        extract($arrayarams);

        require $this->viewPath . 'base/view.header.php';
        require $this->viewPath . 'view.' . $view . '.php';
        require $this->viewPath . 'base/view.footer.php';
    }


    protected function getNavigation()
    {
        $navigation = $this->navigation;
        if(!$navigation) return;
        ob_start();
        require $this->viewPath . 'base/view.navigation.php';
        return ob_get_clean();
    }


    protected function getRender($view, $data = [])
    {
        if (is_array($view)) {
            $viewPath = $this->viewPath;
            for ($i = 0; $i < count($view) - 1; $i++) {
                $viewPath .= $view[$i] . '/';
            }
            $viewPath .= 'view.' . $view[count($view) - 1] . '.php';
        } 
        else {
            $viewPath = $this->viewPath . 'view.' . $view . '.php';
        }
        extract($data);

        ob_start();
        require $viewPath;
        return ob_get_clean();
    }

    
    protected function gotoUrl($url)
    {
        header('Location: ' . SITE_URL . $url);
        die();
    }    
    
}