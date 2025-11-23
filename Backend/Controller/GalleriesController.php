<?php

namespace Backend\Controller;

use Backend\Controller\Controller;
use Backend\Model\BlocsModel;
use Backend\Model\GalleriesModel;
use Backend\Model\GalleriesPhotosModel;
use Backend\Model\PagesModel;
use Core\Form\Select;
use Core\PhotosManager;
use Core\Util;

class GalleriesController extends Controller
{
    private $galleriesUrl = DWNLD_GALLERIES_URL;
    private $galleriesPath = DWNLD_GALLERIES_PATH;
    private $photosManager;
    private $galleriesModel;
    private $galleriesPhotosModel;
    private $pagesBlocsModel;
    private $pagesModel;

    private $typesList = [
        'Slider : changement d\'image par glissement' => 1,
        'Fading : changement d\'image par transparence' => 2,
        'Zoom : changement d\'image par effet zoom' => 3,
        'Grid : mise en page en grille' => 4,
        'Grid diaporama : Mise en page grille, diaporama' => 5 
    ];

    private $margesList = [
        10 => 10,
        20 => 20,
        30 => 30,
        40 => 40,
        50 => 50,
        60 => 60,
        70 => 70,
        80 => 80,
        90 => 90,
        100 => 100
    ];

    private $colonnesList = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4
    ];


    public function __construct()
    {
        parent::__construct();        
        $this->galleriesModel = new GalleriesModel();
        $this->galleriesPhotosModel = new GalleriesPhotosModel();        
        $this->pagesBlocsModel = new BlocsModel();
        $this->pagesModel = new PagesModel();
        $this->photosManager = new PhotosManager();
        $this->photosManager->setPhotoType(PHOTOS_TYPE);
    }


    public function getHome()
    {
        $this->photosManager->removeTempDirectories($this->galleriesPath);
        $galleries = $this->galleriesModel->getAll();
        $this->render('galleries', [
            'title'     =>  'Administration - Galleries',
            'styles'    =>  'admin',
            'galleries' =>  $galleries
        ]);
    }


    public function getInsert()
    {
        $this->render('gallery', [
            'title'     =>  'Administration - Gallerie',
            'styles'    =>  'admin',
            'step'      =>  'insert'
        ]);
    }


    public function postInsert()
    {
        /**
         *  Mysql gallerie default values
         */
        $nom = 'Nom par défault n°' . rand(1, 10000);
        $type = 1;
        $hauteur = '';
        $colonnes = '';
        $marges = '';

        foreach ($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }                
        $this->galleriesModel->insert([
            'nom'       =>  [$nom, 's'],
            'type'      =>  [$type, 'i'],
            'hauteur'   =>  [$hauteur, 's'],
            'colonnes'  =>  [$colonnes, 's'],
            'marges'    =>  [$marges, 's']
        ]);
        $galleryId = $this->galleriesModel->lastId();
        echo json_encode(['redirectUrl' => 'admin/galeries/update/' . $galleryId]);
    }


    public function getUpdate($id)
    {          
        /**
        * 1. Create temp gallery directory if not exist
        */ 
        if(!$this->photosManager->directoryExist($this->galleriesPath . '_'. $id)) {
            $this->photosManager->copyDirectory($this->galleriesPath . $id, $this->galleriesPath . '_'. $id);
        }        

        /**
        * 2. Get current gallery
        */ 
        $gallery = $this->galleriesModel->getSingle($id);

        /**
        * 3. Get photos from bdd
        */ 
        $photos = [];
        if($this->galleriesPhotosModel->hasDatasPhotos($gallery->id)) { 
           foreach($this->galleriesPhotosModel->getDatasPhotos() as $photo) {
            $photos[] = (object) [
                'id'    => $photo->id,
                'ordre' => $photo->ordre,
                'titre' => $photo->titre,
                'texte' => nl2br($photo->texte),
                'lien'  => $photo->lien,
                'filename'  => $photo->id . '.' . $this->photosManager->photosType
            ];
           }
        }

        /**
         * 4. Create select lists
         */
        $margesSelect = new Select('marges', 'Marges', $this->margesList, $gallery->marges);
        $typesSelect = new Select('type', 'Type', $this->typesList, $gallery->type);
        $colonnesSelect = new select('colonnes', 'Colonnes', $this->colonnesList, $gallery->colonnes);                


        /**
         * 5. Render via template
         */
        $this->render('gallery', [
            'title'             =>  'Administration - Gallerie',
            'styles'            =>  'admin',
            'gallery'           =>  $gallery,
            'photos'            =>  $photos,
            'step'              =>  'update',
            'urlSmall'          =>  $this->galleriesUrl . '_'.$id . '/small/',
            'urlLarge'          =>  $this->galleriesUrl . '_'.$id . '/large/',            
            'margesSelect'      =>  $margesSelect->getHTML(),
            'typesSelect'       =>  $typesSelect->getHTML(),
            'colonnesSelect'    =>  $colonnesSelect->getHTML()
        ]);
    }


    public function postUpdate()
    {          
        foreach ($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }     

        /**
         * 0. Update gallery datas
         */
        $this->galleriesModel->update([
            'nom'       =>  [$nom, 's'],
            'type'      =>  [$type, 'i'],
            'hauteur'   =>  [$hauteur, 's'],
            'colonnes'  =>  [$colonnes, 's'],
            'marges'    =>  [$marges, 's']
        ], 'id='.intval($gallery_id));

        /**
         * $currentOrder : photos sorting order 
         */
        $currentOrder = 0;
    
        /**
        * 1. Is there photos in bdd ? => delete them
        */ 
        if($this->galleriesPhotosModel->hasDatasPhotos($gallery_id)) {
            $this->galleriesPhotosModel->delete('gallerie_id='.intval($gallery_id));
        }

        /**
        * 2. Is there photos in posts ? => insert into bdd, rename each existing file with last inserted id
        */         
        if(isset($textes) && count($textes)>0) {
            $photosNumber = count($textes);
            for($i=0; $i<$photosNumber; $i++) {
                $photo = (object) [
                    'id'    =>  $ids[$i],
                    'ordre' => $ordres[$i],
                    'titre' => $titres[$i],
                    'texte' => $textes[$i],
                    'lien'  => $liens[$i],
                    'gallerie_id' =>  $gallery_id
                ];                
                $currentOrder++;
                $lastId = $this->galleriesPhotosModel->insertDatasPhotos([$photo]);
                $this->photosManager->renamePhoto($this->galleriesPath, '_'.$gallery_id, $ids[$i], $lastId, $this->photosManager->photosType);
            }           

        }

        /**
        * 3. Is there photos in files ? => insert each file into bdd, upload each new file and name it with last inserted id
        */
        if(isset($this->httpRequest->files->photos) && !empty($this->httpRequest->files->photos)) {
            $uploadedPhotos = $this->normalizeUploadedFiles($this->httpRequest->files->photos);
            $photosNumber = count($uploadedPhotos);
            $currentOrder++;  
            if(isset($uploadedPhotos) && $photosNumber>0) {
                for($i=0; $i<$photosNumber; $i++) {
                    $photo = (object) [
                        'ordre' => $currentOrder,
                        'titre' => '',
                        'texte' => '',
                        'lien'  => '',
                        'gallerie_id' =>  $gallery_id
                    ];
                    $currentOrder++;
                    $lastId = $this->galleriesPhotosModel->insertDatasPhotos([$photo]);
                    $this->photosManager->addPhoto($uploadedPhotos[$i], $this->galleriesPath, '_'.$gallery_id, $lastId);
                }
            }      
        }
          
        
        /**
        * 4. Delete previous gallery directory and replace it by gallery temp directory
        **/ 
        $this->photosManager->removeDirectory($this->galleriesPath . $gallery_id);
        if(
            file_exists($this->galleriesPath . '_'.$gallery_id) &&
            is_dir($this->galleriesPath . '_'.$gallery_id)
        ) {
            rename($this->galleriesPath . '_'.$gallery_id, $this->galleriesPath . $gallery_id);
        }

        /**
        * 5. Redirect to update url
        */         
        echo json_encode(['redirectUrl' => 'admin/galeries/update/' . $gallery_id]);      
    }


    public function getDelete($id) 
    {        
        foreach ($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }

        $titre = $this->galleriesModel->getSingle($id)->nom;

        $element = (object) [
            'legend'    =>  'Supprimer une gallerie',
            'titre'     =>  $titre,
            'id'        =>  $id,
            'slug'      =>  'galeries'
        ];

        $this->render('any-delete', [
            'title'    =>  'Administration - Pages',
            'styles'   =>  'admin',
            'element'  =>  $element
        ]);
    }


    public function postDelete($id) 
    {        
        foreach ($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }        
        $blocs = $this->pagesBlocsModel->select('type="gallery" AND content='.util::escape($id));
        $pages = [];

        foreach($blocs as $bloc) {
            $page = $this->pagesModel->getSingle($bloc->page_id);
            if($page) {
                $pages[] = $page->titre;
            }            
        }
        if(count($pages)>0) {

            $message = ''
            . 'Suppresion impossible, '
            . 'car les page(s) <strong>'. implode(', ', $pages) .'</strong> utilise(nt) cette gallerie.';

            $element = (object) [
                'legend'    => 'Supprimer une gallerie',
                'message'   => $message,
                'slug'      => 'galeries'
            ];

            $this->render('any-delete', [
                'title'    =>  'Administration - Pages',
                'styles'   =>  'admin',
                'element'  =>  $element
            ]);
            
        }
        else{
            
            $this->galleriesModel->delete('id='. intval($id));
            $this->galleriesPhotosModel->delete('gallerie_id='. intval($id));
            $this->photosManager->removeDirectory($this->galleriesPath. $id);            
            $this->gotoUrl('admin/galeries');           
            
        }
        
    }


    public function postUpdatePhotos()
    {        
        foreach ($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }
        $fileName       =   $photo . '.' . $this->photosManager->photosType;
        $directoryId    =   $gallery;
        $photosPath     =   $this->galleriesPath;

        switch($action) {
            case 'backward':
                $this->photosManager->backwardPhoto($fileName, '_'.$directoryId, $photosPath);
            break;

            case 'forward':
                $this->photosManager->forwardPhoto($fileName, '_'.$directoryId, $photosPath);
            break;

            case 'delete':
                $this->photosManager->deletePhoto($fileName, '_'.$directoryId, $photosPath);
            break;
        }
    }


    /* --------------- Private utilities --------------- */
    

    private function getPhotos($path)
    {
        $scandir = false;
        if (is_dir($path)) $scandir = scandir($path);
        $photos = ($scandir !== false) ? $scandir : [];
        return array_values(array_diff($photos, ['.', '..']));
    }


    private function normalizeUploadedFiles(&$files)
    {   
        if(
            !isset($files) || 
            empty($files) || 
            !isset($files['name']) ||
            empty($files['name'])
        ) 
        return [];        
        $normalizedFiles    =   [];
        $filesCount         =   count($files['name']);
        $filesKeys          =   array_keys($files);
        for ($i = 0; $i < $filesCount; $i++) {
            foreach ($filesKeys as $key) {
                $normalizedFiles[$i][$key] = $files[$key][$i];
            }
        }
        return $normalizedFiles;
    }


    private function createDirectory($id)
    {
        if (!is_dir($this->galleriesPath . $id)) {
            mkdir($this->galleriesPath . $id, 0777);
        }
    }


    private function getSlugyFilename($string)
    {
        $filename = substr($string, 0, strrpos($string, '.'));
        $extension = substr($string, (strrpos($string, '.') + 1));
        $slug = Util::getSlug($filename);
        return $slug . '-' . rand(1, 100000) . '.' . $extension;
    }

    
    private function getLastNumFile($path)
    {
        $photos = $this->getPhotos($path);
        $lastPhoto = end($photos);
        $parts = explode('-', $lastPhoto);
        return intval($parts[0]);
    }

   
}
