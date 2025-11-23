<?php

namespace Frontend\Controller;

use Core\Controller\Controller as CoreController;
use Core\Util;
use Backend\Model\PagesModel;

class Controller extends CoreController
{
    protected $viewPath = SITE_PATH . 'Frontend/View/';
    protected $navigation = NAVIGATION;
    protected $frenchDate;   
    private $pagesModel;    
    protected $pageTypes = [
        ''  => '',
        0 => 'small',
        1 => 'large',
        2 => 'xlarge'
    ]; 

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
        $today = date('Y-m-d');
        $this->frenchDate = Util::dateToFrench(date('Y-m-d'), 'l j F Y');
        Util::deleteDirectory(SITE_PATH.'exposants');
    } 
    
    
    public function getPageType($slug)
    {
        if(!isset($this->pagesModel)) {
            $this->pagesModel= new PagesModel();
        }
        $page = $this->pagesModel->getSingleBySlug($slug);
        if(is_object($page))
            return ' '. $this->pageTypes[$page->type];
        
        return '';
    }



    protected function getWishlistWidgetRender($type=null, $slug=null)
    {        
        $length = count($_SESSION['wishlist']);
        $header = '';

        if($length>0) {
            $header = $this->getRender(['wishlist', 'widget-header'], [            
                'wishlistWidget' => (object) [                    
                    'length'    => $length
                ]
            ]);
        }

        $main = $this->getRender(['wishlist', 'widget-main'], [            
            'wishlistWidget' => (object) [                
                'type'      => $type,
                'slug'      => $slug
            ]
        ]);

        return (object) [
            'header' => $header,
            'main'  => $main
        ];

    }


}