<?php

namespace Backend\Controller;

use Backend\Controller\Controller;
use Backend\Model\BlocsModel;
use Backend\Model\GalleriesModel;
use Backend\Model\PagesModel;
use Core\Form\Select;
use Core\Util;

class PagesController extends Controller
{
    private $pagesModel;
    private $blocsModel;
    private $galleriesModel;    

    private $images_url     =   DWNLD_IMAGES_URL;
    private $files_url      =   DWNLD_FILES_URL;
    private $images_path    =   DWNLD_IMAGES_PATH;
    private $files_path     =   DWNLD_FILES_PATH;
    private $pageTypes = [
        '...'       =>  null,
        'Small'     =>  0,
        'Large'     =>  1,
        'Xlarge'    =>  2
    ];

    public function __construct()
    {
        parent::__construct();
        $this->pagesModel = new PagesModel();
        $this->blocsModel = new BlocsModel();
        $this->galleriesModel   = new GalleriesModel();
    }


    public function getHome()
    { 
        $pages = $this->pagesModel->getAllOrderBy('slug');
        $this->render('pages', [
            'title'     =>  'Administration - Pages',
            'styles'    =>  'admin',
            'pages'     =>  $pages
        ]);
    }


    public function getInsert()
    {        
        $this->render('page', [
            'title'     =>  'Administration - Pages',
            'styles'    =>  'admin',
            'step'      =>  'insert'
        ]);
    }

    public function postInsert()
    {  
        foreach($this->httpRequest->post as $key => $value) {
            $$key = $value;
        }
        $this->pagesModel->insert([
            'titre'     =>  [$titre, 's'],            
            'slug'      =>  [Util::getSlug($titre), 's'],
            'type'      =>  ['', 's']
        ]);
        $id = $this->pagesModel->lastId();
        $this->gotoUrl('admin/pages/update/'. $id);
    }


    public function getUpdate($id)
    {        
        $page = $this->pagesModel->getSingle($id);
        $blocs = $this->blocsModel->getAllByPageIdOrderByOrdre($id);
        $page->blocs = ''; 

        foreach($blocs as $bloc) {
            
            $brutContent = '';
            $displayContent = '';

            switch($bloc->type) {

                case 'image': 
                    $brutContent = $bloc->content;
                    if(file_exists( $this->images_path . $bloc->content)) {
                        $displayContent = '<a href="'. $this->images_url . $bloc->content .'" target="_blank"><img src="'. $this->images_url . $bloc->content .'"></a>';
                    }
                break;


                case 'file':
                    $brutContent = $bloc->content;
                    if(file_exists( $this->files_path . $bloc->content)) {
                        $displayContent = '<a href="'. $this->files_url . $bloc->content .'" target="_blank">'. $bloc->content .'</a>';
                    }
                break;


                case 'text':
                    $displayContent = $bloc->content;
                break;


                case 'gallery':
                    $galleriesOptionsList = $this->getGalleriesOptionsList();
                    $select = new Select('gallery_'. $bloc->ordre, '', $galleriesOptionsList, $bloc->content, false, ['attributes' => ['data-type' => 'gallery']]);
                    $displayContent = $select->getHTML();
                break;


                case 'code':
                    $displayContent = $bloc->content;
                break;

            }

            $displayColonnes = $this->getDisplayColonnesRender($bloc->ordre, $bloc->colonnes);
            $displayOrdre = $this->getDisplayOrdresRender($blocs, $bloc->ordre, $bloc->page_id);
            $displayMarge = $this->getDisplayMargesRender($bloc->ordre, @$bloc->marge);            


            $page->blocs .= $this->getRender('pages-bloc', [
                'num'               =>  $bloc->ordre,
                'type'              =>  $bloc->type,
                'displayContent'    =>  $displayContent,
                'brutContent'       =>  $brutContent,
                'displayOrdre'      =>  $displayOrdre,
                'displayColonnes'   =>  $displayColonnes,
                'displayMarge'      =>  $displayMarge
            ]);

        }

        $displayLargeur = new Select('type', '', $this->pageTypes, $page->type, false, []);

        $this->render('page', [
            'title'             =>  'Administration - Pages',
            'styles'            =>  'admin',
            'page'              =>  $page,
            'ckeditor'          =>  true,
            'step'              =>  'update',
            'displayLargeur'    =>  $displayLargeur->getHTML()
        ]);
    }


    public function postUpdate()
    {   
        foreach($this->httpRequest->post as $key => $value) {  
            $$key = $value;
        }
        
        $this->pagesModel->update([
            'titre'     => [$titre, 's'],            
            'slug'      => [$slug, 's'],
            'type'      => [$type, 's']
        ], 'id='. intval($id));

        $this->blocsModel->delete('page_id='. intval($id));
        
        foreach($this->httpRequest->post as $key => $value) {

            $type = $this->getType($key);
            if($type === 'columns') continue;

            if($type !== false && !empty($value)) {

                $order = substr($key, strpos($key, '_') + 1);
                $content = $value;
                
                /**
                 *  Set bloc margin bottom : default 30 
                 */
                $marge = '30';
                $margin = 'margin_'.$order;
                if(isset($$margin)) $marge = $$margin;

                /**
                 * Set columns number for text bloc only : default void
                 */
                $colonnes = '';
                if($type === 'text') {
                    $columns = 'columns_'.$order;
                    if(isset($$columns))  $colonnes = $$columns;
                }

                $this->blocsModel->insert([
                    'page_id'       =>  [$id, 'i'],
                    'type'          =>  [$type, 's'],
                    'content'       =>  [$content, 's'],
                    'ordre'         =>  [$order, 'i'],
                    'temp'          =>  [0, 'i'],
                    'colonnes'      =>  [$colonnes, 's'],
                    'marge'         =>  [$marge, 's']
                ]);
            }
        }
        
        foreach($this->httpRequest->files as $key => $value) {
            $content = null;
            $order = substr($key, strpos($key, '_') + 1);
            $filename = $this->getSlugyFilename($value['name']);
            $type = $this->getType($key);
            if($type !== false && !empty($value['tmp_name'])) {
                $pathname = $type . 's_path';
                $colonnes = '';
                $marge = '';
                if(move_uploaded_file($value['tmp_name'], $this->$pathname . $filename)) {
                    $this->blocsModel->insert([
                        'page_id'   =>  [$id, 'i'],
                        'type'      =>  [$type, 's'],
                        'content'   =>  [$filename, 's'],
                        'ordre'     =>  [$order, 'i'],
                        'temp'      =>  [0, 'i'],
                        'colonnes'  =>  [$colonnes, 's'],
                        'marge'     =>  [$marge, 's']
                    ]);
                }
            }
        }        
        $this->gotoUrl('admin/pages/update/'. $id);
    }


    public function getDelete($id) 
    {        
        foreach ($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }

        $titre = $this->pagesModel->getSingle($id)->titre;

        $element = (object) [
            'legend'    =>  'Supprimer une page',
            'titre'     =>  $titre,
            'id'        =>  $id,
            'slug'      =>  'pages'
        ];

        $this->render('any-delete', [
            'title'     =>  'Administration - Pages',
            'styles'    =>  'admin',
            'element'   => $element
        ]);
    }


    public function postDelete($id) 
    {   
        $this->pagesModel->delete('id='. intval($id));
        $blocs = $this->blocsModel->getAllByPageIdOrderByOrdre($id);       

        foreach($blocs as $bloc) {
            if($bloc->type === 'image') {
                $slug = $bloc->content;
                unlink($this->images_path . $slug);
            }
            if($bloc->type === 'file') {
                $slug = $bloc->content;                
                unlink($this->files_path . $slug);
            }
        }        
        $this->blocsModel->delete('page_id='. intval($id));
        $this->gotoUrl('admin/pages');
    }
    
    
    public function postUpdateOrder()
    {        
        foreach($this->httpRequest->post as $key => $value) {  
            $$key = $value;
        }
        $this->blocsModel->updateOrder($currentOrder, $newOrder, $currentId);
        echo json_encode([
            "result" => "success"
        ]);
    }


    public function postAddBloc()
    {
        foreach($this->httpRequest->post as $key => $value) {  
            $$key = $value;
        }
        $displayContent = '';
        if($type === 'gallery') {
            
            $galleriesOptionsList = $this->getGalleriesOptionsList();
            $select = new Select('gallery_'. $num, '', $galleriesOptionsList, null, false, [
                'attributes' => [
                    'data-type' => 'gallery'
                ]
            ]);
            $displayContent = $select->getHTML();
        }
        $displayColonnes = null;

        if($type==='text') {

            $displayColonnes = $this->getDisplayColonnesRender($num, null);
        }
        $bloc = $this->getRender('pages-bloc', [
            'num'               =>  $num,
            'type'              =>  $type,
            'displayContent'    =>  $displayContent,
            'displayColonnes'   =>  $displayColonnes
        ]);
        echo $bloc;
    }


    public function filesUpload()
    {   
        foreach($this->httpRequest->files as $key => $value) {  
            $$key = $value;
        }
        if(!empty($value['name'])) {
            $source_file_name = $value['name'];
            $source_file = $value['tmp_name'];
            $array = explode(".", $source_file_name);
            $extension = end($array);
            $target_file_name = $this->getSlugyFilename($source_file_name);
            chmod($this->images_path, 0777);
            $allowed_extension = ['jpg', 'gif', 'png', 'bmp'];
            if(in_array($extension, $allowed_extension)) {
                $target_path = $this->images_path . $target_file_name;
                $target_url = $this->images_url . $target_file_name;
                move_uploaded_file($source_file, $target_path);
                $ckeditor_function_number = $_GET['CKEditorFuncNum'];
                $message = '';
                echo $this->getScript($ckeditor_function_number, $target_url, $message);
            }
        }
    }



    /* --------------- Private utilities --------------- */


    private function getDisplayColonnesRender($currentOrdre, $currentColonnes)
    {
        $colonnesOptionsList = $this->getColonnesOptionsList();
        $displayColonnes = '<label>Colonnes</label>';
        $select = new Select('columns_'.$currentOrdre, '', $colonnesOptionsList, $currentColonnes, false, [
            'widgetClass' => 'small', 
            'attributes' => [
                'data-type' => 'columns', 
                'data-columns' => $currentColonnes
            ]
        ]);

        return $displayColonnes.$select->getHTML();        
    }


    private function getDisplayOrdresRender($blocs, $currentOrdre, $pageId)
    {
        $ordresOptionsList = $this->getOrdresOptionsList($blocs);        
        $displayOrdre = '<label>Ordre</label>';        
        $select = new Select('ordre_'.$currentOrdre, '', $ordresOptionsList, $currentOrdre, false, [
            'widgetClass' => 'small', 
            'attributes' => [
                'data-type' => 'order', 
                'data-current-order' => $currentOrdre, 
                'data-id' => $pageId
            ]
        ]);

        return $displayOrdre.$select->getHTML();
    }


    private function getDisplayMargesRender($currentOrdre, $currentMarge)
    {
        $margesOptionsList = $this->getMargesOptionsList();
        $displayMarges = '<label>Marge</label>';

        $select = new Select('margin_'.$currentOrdre, '', $margesOptionsList, $currentMarge, false, [
            'widgetClass' => 'small', 
            'attributes' => [
                'data-type' => 'margin', 
                'data-current-margin' => $currentMarge
            ]
        ]);

        return $displayMarges.$select->getHTML();
    }


    private function getColonnesOptionsList()
    {
        $colonnes = [];        
        for($i=1; $i<=4; $i++) {
            $colonnes[$i] = $i;
        }
        return $colonnes;
    }


    private function getOrdresOptionsList($blocs)
    {
        $ordres = [];        
        for($i=1; $i<=count($blocs); $i++) {
            $ordres[$i] = $i;
        }
        return $ordres;
    }
    
    
    private function getMargesOptionsList()
    {
        $marges = [];
        for($i=10; $i<=100; $i+=10) {
            $marges[$i] = $i;
        }
        return $marges;
    }


    private function getScript($ckeditor_function_number, $target_url, $message) 
    { 
        return ''
        .'<script type="text/javascript">'
        .'window.parent.CKEDITOR.tools.callFunction('. $ckeditor_function_number .', "'. $target_url .'", "'. $message .'")'
        .'</script>';
    }


    private function getSlugyFilename($string) 
    {
        $filename = substr($string, 0, strrpos($string, '.'));
        $extension = substr($string, (strrpos($string, '.') + 1));
        $slug = Util::getSlug($filename);
        return $slug . '-' . rand(1, 100000) . '.' . $extension;
    }
    

    private function getType($key)
    {
        if(strpos($key, 'text_') !== false) {
            return 'text';
        }
        if(strpos($key, 'image_') !== false) {
            return 'image';
        }
        if(strpos($key, 'file_') !== false) {
            return 'file';
        }
        if(strpos($key, 'gallery_') !== false) {
            return 'gallery';
        }
        if(strpos($key, 'columns_') !== false) {
            return 'columns';
        }
        if(strpos($key, 'code_') !== false) {
            return 'code';
        }
        return false;
    }


    private function getGalleriesOptionsList()
    {
        $galleries = $this->galleriesModel->getAll();        
        $galleriesOptionsList = $this->getOptionsList($galleries, 'id', 'nom'); 
        return $galleriesOptionsList;        
    }


    private function getOptionsList($list, $propertyName, $propertyValue)
    {
        $optionsList = [];
        foreach($list as $item) {
            $optionsList[$item->$propertyValue] = $item->$propertyName;
        }
        return $optionsList;
    }

}


