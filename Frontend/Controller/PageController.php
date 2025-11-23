<?php

namespace Frontend\Controller;

use Frontend\Controller\Controller;
use Frontend\PageManager;
use Backend\Model\PagesModel;

class PageController extends Controller
{
    private $pageManager;    
    private $pagesModel;

    public function __construct()
    {
        parent::__construct();
        $this->pageManager = new PageManager();
        $this->pagesModel = new PagesModel();
    }

    public function getDisplay($slug)
    {
        $allowedSlugs = $this->getAllowedSlugs();

        if(!in_array($slug, $allowedSlugs)) {
            $this->gotoUrl('notfound');
        }

        $content = $this->pageManager->getContent($slug);
        $currentPage = $this->pageManager->current;
        $title = ($currentPage !== null)? ' - '. $currentPage->titre : ''; 
        
        $this->render('page', [
            'title'             => 'Salon primevère' . $title,
            'styles'            => 'page',
            'content'           => $content,
            'date'              => $this->frenchDate,
            'type'              => $this->getPageType($slug),
            'wishlistWidget'    => $this->getWishlistWidgetRender()
        ]);
    }
    
    
    private function getAllowedSlugs()
    {
        $slugs = $this->pagesModel->getAll('slug');
        return $slugs;
    }

}