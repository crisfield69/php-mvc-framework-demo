<?php

namespace Frontend\Controller;

use Frontend\Controller\Controller;
use Frontend\PageManager;

class HomeController extends Controller
{ 
    private $pageManager;

    public function __construct()
    {
        parent::__construct();
        $this->pageManager = new PageManager();        
    }


    public function getDisplay()
    {  
        $content = $this->pageManager->getContent('accueil');

        $this->render('home', [
            'title'             => 'Salon primevère',
            'styles'            => 'home',
            'content'           => $content,
            'type'              => $this->getPageType('accueil'),
            'wishlistWidget'    =>  $this->getWishlistWidgetRender()
        ]);
    }    

}