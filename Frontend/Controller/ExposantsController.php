<?php

namespace Frontend\Controller;

use Frontend\Controller\Controller;
use Frontend\Model\CategoriesModel;
use Frontend\Model\ExposantsModel;
use Core\Util;
use Frontend\PageManager;

class ExposantsController extends Controller
{
    private $categoriesModel;
    private $exposantsModel;
    private $currentUrl;
    private $currentCategorySlug;
    private $currentExposantSlug;
    private $currentArguments;
    private $pageManager;
    private $voidPage = 'exposants-vide';


    public function __construct()
    {     
        parent::__construct();
        $this->categoriesModel = new CategoriesModel();
        $this->exposantsModel = new ExposantsModel();
        $this->currentUrl = null;
        $this->currentCategorySlug = null;
        $this->currentExposantSlug = null;
        $this->currentArguments= [];
        $this->pageManager = new PageManager();
    }


    public function getHome()
    {        
        $categories =   $this->getCategoriesRender();       
        $breadcrumb =   $this->getBreadcrumbRender();        

        $void       =   $this->getVoid();       
        $void       =   $this->getRender(['exposants', 'void'], ['content' => $void]); 
            
        $this->render('exposants', [
            'styles'            =>  'exposants',
            'title'             =>  'Exposants',
            'categories'        =>  $categories,
            'breadcrumb'        =>  $breadcrumb,
            'void'              =>  $void,
            'type'              =>  $this->getPageType('exposants'),
            'wishlistWidget'    =>  $this->getWishlistWidgetRender()
        ]);
    }    

    private function getVoid()
    {        
        return $this->pageManager->getContent($this->voidPage);
    }


    public function getContent(...$arguments)
    {
        $this->currentArguments = $arguments;
        if($this->isExposant(end($arguments))) {
            $this->currentExposantSlug = end($arguments);
            array_pop($arguments);
        }        
        $this->currentUrl = implode('/', $arguments);
        $this->currentCategorySlug = end($arguments);

        if($this->currentExposantSlug === null) {
            $categoriesSlugs = $this->categoriesModel->getAllSlugs();
            foreach($this->currentArguments as $argument) {
                if(in_array($argument, $categoriesSlugs)) {
                    break;
                }
            }
        }

        $wishlistWidget = $this->getWishlistWidgetRender('exposant', $this->currentExposantSlug);

        $categories             =   $this->getCategoriesRender();
        $exposants              =   $this->getExposantsRender();
        $exposant               =   $this->getExposantRender($wishlistWidget);
        $breadcrumb             =   $this->getBreadcrumbRender();
        $this->render('exposants', [
            'styles'            => 'exposants',
            'title'             => 'Exposants',
            'categories'        =>  $categories,
            'exposants'         =>  $exposants,
            'exposant'          =>  $exposant,
            'breadcrumb'        =>  $breadcrumb,
            'type'              =>  $this->getPageType('exposants'),
            'wishlistWidget'    =>  $wishlistWidget
        ]);
    }


    private function getBreadcrumbRender()
    {  
        $currentUrl = SITE_URL . 'exposants/';
        $html = ''
        .'<ul>'
        .'<li>'
        .'<a href="' . $currentUrl . '">Exposants</a>'
        .'</li>';
        foreach($this->currentArguments as $argument) {
            $category = $this->getCategoryBySlug($argument);
            if($category !== null) {
                $currentUrl .= $argument; 
                $html .= '<li>';
                $html .= '<a href="' . $currentUrl . '">' . $category->nom .'</a>';
                $html .= '</li>';
                $currentUrl .= '/';
            }
        }
        $html .= '</ul>';
        return $html;
    }


    private function getExposantRender($wishlistWidget)
    {
        $exposant = $this->exposantsModel->getSingleBySlug($this->currentExposantSlug);
        if($exposant !== null) {

            $photosRender = null;
            $photos = $this->getPhotos($exposant->id);
            if(count($photos)) {
                $photosRender = $this->getRender(['exposants', 'photos'], [
                    'photos' => $photos
                ]);
            }
            return $this->getRender(['exposants', 'exposant'], [
                'exposant'          =>  $exposant,
                'wishlistWidget'    =>  $wishlistWidget,
                'photos'            =>  $photosRender,
                'capitalize'        =>  function ($string) {
                    return $this->getCapitalize($string);
                },
                'writeclean' => function ($string, $value) {
                    return $this->writeClean($string, $value);
                }
            ]);
        }
        return null;
    }


    private function getPhotos($id)
    {
        $photos = [];
        $imagesPath = EXPOSANTS_IMAGES_PATH . '/users/' . $id . '/';
        $imagesUrl = EXPOSANTS_IMAGES_URL . '/users/' . $id . '/';
        if(is_dir($imagesPath)) {
            $openDir = opendir($imagesPath);
            while(false !== ($file = readdir($openDir))) {
                if($file != '.' && $file != '..' && is_file($imagesPath.'small/'.$file)) {
                    $photos[] = (object) [
                        'small' => $imagesUrl.'small/'.$file,
                        'large' => $imagesUrl.'large/'.$file,
                    ]; 
                }
            }
            closedir($openDir);
        }
        return $photos;
    }


    private function getExposantsRender()
    {   
        $exposants = null;
        $exposants = $this->exposantsModel->getAllByCategorySlug($this->currentCategorySlug);
        if($exposants !== null){
            $html = '<ul>';
            foreach($exposants as $exposant) {
                $exposant->url = SITE_URL . 'exposants/' . $this->currentUrl . '/' . Util::getSlug($exposant->name);
                $html .= $this->getRender(['exposants', 'exposants'], [
                    'exposant'      => $exposant,
                    'capitalize'    => function ($string) {
                        return $this->getCapitalize($string);
                    },
                    'writeclean' => function ($string, $value) {
                        return $this->writeClean($string, $value);
                    }
                ]);
            }
            $html .= '</ul>';
            return $html;
        }
        return null;
    }


    private function getCapitalize($string)
    {        
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }


    private function writeClean($string, $value)
    {
        if(!empty($value)) {
            echo $string . $value;
        }
    }


    private function getCategoriesRender()
    {   
        $categoriesByParent = $this->categoriesModel->getAllByParentOrdre();
        $categories = [];
        foreach($categoriesByParent as $category) {
            $category->slug = Util::getSlug($category->nom);
            $category->url = $this->getCategoryUrl($category);
            if (!isset($categories[$category->parent_id])) {
               $categories[$category->parent_id] = [];
            }
           $categories[$category->parent_id][] = $category;
        }
        $categoriesHTMLTree = $this->getCategoriesHTMLTree($categories, $categories[0]);
        return $categoriesHTMLTree;
    }   


    private function getCategoriesHTMLTree($categories, $currentCategory)
    {
        $categoriesTree = '<ul>';
        foreach ($currentCategory as $key => $category) {
            $id = $category->id;
            $class = '';
            if (
                $this->currentUrl !== null &&
                $this->currentCategorySlug !== null
            ) 
            { 
               if ($category->url === $this->currentUrl) {
                    $class = ' class="selected"';
                } 
                else {
                    if (in_array($category->slug, $this->currentArguments)) {
                        $class = ' class="opened"';
                    }
                }
            }
            $categoriesTree .= ''
                . '<li' . $class . ' data-type="category-slug" data-category-slug="'.$category->slug.'">'
                . '<h3>'
                . '<a href="' . SITE_URL . 'exposants/' . $category->url . '">' . $category->nom . '</a>'
                . '</h3>';
            if (isset($categories[$id])) {
                $categoriesTree .= $this->getCategoriesHTMLTree($categories, $categories[$id]);
            }
            $categoriesTree .= ''
                . '</li>';
        }
        $categoriesTree .= '</ul>';
        return $categoriesTree;
    }


    private function getCategoryUrl($category)
    {
        $slug = $category->slug;
        $url = '';
        if ($category->parent_id == 0) {
            $url = $slug;
        } else {
            $category = $this->categoriesModel->getSingle($category->parent_id);
            if ($category !== false) {
                $category->slug = Util::getSlug($category->nom);
                $url = $this->getCategoryUrl($category) . '/' . $slug;
            }
        }
        return $url;
    }


    private function getCategoryBySlug($slug)
    {
        $categories = $this->categoriesModel->getAllByParentOrdre();
        foreach ($categories as $category) {
            if (Util::getSlug($category->nom) === $slug) {
                return $category;
            }
        }
        return null;
    }


    private function isExposant($currentCategorySlug)
    {
        $exposant = null;
        $exposant = $this->exposantsModel->getSingleBySlug($currentCategorySlug);
        if($exposant !== null) {
            return true;
        }
        return false;
    }


    private function getCategoriesTree($categories, $currentCategory)
    {
        $categoriesTree = [];
        foreach ($currentCategory as $key => $category) {
            $id = $category->id;
            if (isset($categories[$id])) {
                $category->children = $this->getCategoriesTree($categories, $categories[$id]);
            }
            $categoriesTree[] = $category;
        }
        return $categoriesTree;
    } 


    private function getExposants()
    {
        $exposants = null;
        $currentCategory = null;
        $currentCategory = $this->getCategoryBySlug($this->currentCategorySlug);
        if ($currentCategory !== null) {
            $exposants = $this->exposantsModel->getAllByCategory($currentCategory->id);
        }        
        return $exposants;
    }
    
}
