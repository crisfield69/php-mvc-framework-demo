<?php

namespace Backend\Controller;

use Frontend\Model\CategoriesModel;
use Backend\Controller\Controller;
use Core\Util;

class CategoriesController extends Controller
{   
    private $categoriesModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoriesModel = new CategoriesModel();
    }


    public function getHome()
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
        if(count($categories)) {
            $categories = $this->getCategoriesHTMLTree($categories, $categories[0]);
        }
        $this->render('categories', [
            'title'         =>  'Administration - Categories',
            'styles'        =>  'admin',
            'categories'    =>  $categories
        ]);
    }


    public function getUpdate($id)
    {        
        $category = $this->categoriesModel->getSingle($id);
        $category->slug = util::getSlug($category->nom);        
        $parents = $this->categoriesModel->getAllWithout($id);

        $ordres = $this->categoriesModel->getOrdresByParent($category->parent_id);
        $this->render('category', [
            'title'     =>  'Administration - Categorie',
            'styles'    =>  'admin',
            'category'  =>  $category,
            'parents'   =>  $parents,
            'ordres'    =>  $ordres
        ]);
    }


    public function postUpdate()
    {
        foreach($this->httpRequest->post as $key => $value) {
            $$key = $value;
        }
        $ordre_nouveau = $ordre_actuel;
        if(intval($parent_nouveau) !== intval($parent_actuel)) {
            $ordre_max = $this->categoriesModel->getMaxOrdreByParent($parent_nouveau);
            $ordre_nouveau = intval($ordre_max + 1);
            $this->categoriesModel->decreaseOrdres($ordre_actuel, $parent_actuel);
        }
        $this->categoriesModel->update([
            'nom'       =>  [$nom, 's'], 
            'parent_id' =>  [$parent_nouveau, 'i'],
            'ordre'     =>  [$ordre_nouveau, 'i']
        ], 'id='.intval($id));
        $this->gotoUrl('admin/categories');
    }


    public function postUpdateOrder()
    {
        foreach($this->httpRequest->post as $key => $value) {
            $$key = $value;
        }
        $this->categoriesModel->updateOrder($currentOrder, $newOrder, $parentId);
        $response =  json_encode([
            'result'    => 'success'
        ]);
        echo $response;
    }
    

    public function postLocked()
    {       
        foreach($this->httpRequest->post as $key => $value) {
            $$key = $value;
        }
        $id = $categoryId;

        $category = $this->categoriesModel->getSingle($id);

        if($category->locked == 0) {
            $locked = 1;
        }
        else {
            $locked = 0;
        }
        $this->categoriesModel->update([
            'locked' =>  [$locked, 'i'],
        ], 'id='.intval($id));

        $response =  json_encode([
            'result'    => 'success'
        ]);
        echo $response;
    }


    /* --------------- Private utilities --------------- */


    private function getCategoriesHTMLTree($categories, $currentCategory)
    {  
        $categoriesTree = '<ul data-parent="'.$currentCategory[0]->parent_id.'">';
        foreach ($currentCategory as $key => $category) {
            $id = $category->id;     
            $ordres = $this->categoriesModel->getOrdresByParent($category->parent_id);

            $categoriesTree .= ''
                . '<li>'
                . '<h3>'. $category->nom .'</h3>'
                . $this->getRender('categories-ordres', [
                    'ordres'    =>  $ordres,
                    'category'  =>  $category
                ])
                . '<a class="button small" href="'. SITE_URL .'admin/categories/update/'. $category->id .'">Modifier</a>';                
            if (isset($categories[$id])) {
                $categoriesTree .= $this->getCategoriesHTMLTree($categories, $categories[$id]);
            }
            $categoriesTree .= ''
                . '</li>';
        }
        $categoriesTree .= '</ul>';
        return $categoriesTree;
    }    


    private function getCategoryUrl($currentCategory)
    {
        $currentCategorySlug = $currentCategory->slug;
        $url = '';
        if ($currentCategory->parent_id == 0) {
            $url = $currentCategorySlug;
        } 
        else {
            $parentCategory = $this->categoriesModel->getSingle($currentCategory->parent_id);
            if ($parentCategory === false || empty($parentCategory))  {
               d($currentCategory);
            }
            else{
                $parentCategory->slug = Util::getSlug($parentCategory->nom);
                $url = $this->getCategoryUrl($parentCategory).'/'.$currentCategorySlug;
            }
        }
      
        return $url;
    }
    
    
}