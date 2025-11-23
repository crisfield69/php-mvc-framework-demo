<?php

namespace Frontend;

use Backend\Model\PagesModel;
use Backend\Model\BlocsModel;
use Backend\Model\GalleriesModel;
use Backend\Model\GalleriesPhotosModel;

class PageManager 
{
    public $current;
    private $pagesModel;
    private $blocsModel; 
    private $galleriesyModel;
    private $galleriesyPhotosModel;   
        
    private $galleriesPath =   DWNLD_GALLERIES_PATH;
    private $imagesUrl     =   DWNLD_IMAGES_URL;
    private $galleriesUrl  =   DWNLD_GALLERIES_URL;
    private $filesUrl      =   DWNLD_FILES_URL;
    private $photosType    =   PHOTOS_TYPE;

    private $galleriesTypes = [
        1   => ['slider', 'slider'],
        2   => ['fading', 'static'],
        3   => ['zoom', 'static'],
        4   => ['grid', 'grid'],
        5   =>  ['grid', 'grid', 'diaporama']
    ]; 
   
    
    private $columnsDefault = 4;
    private $marginDefault = 30;


    public function __construct()
    {
       $this->pagesModel = new PagesModel();
       $this->blocsModel = new BlocsModel();
       $this->galleriesyModel = new GalleriesModel();
       $this->galleriesyPhotosModel = new GalleriesPhotosModel();       
    }

    public function getContent($pageSlug)
    {  
        $content = '';   

        $page = $this->pagesModel->getSingleBySlug($pageSlug);        
        
        if($page) {

            $this->current = $page;
            
            $blocs = $this->blocsModel->getAllByPageIdOrderByOrdre($page->id);
            
            foreach($blocs as $bloc) {

                $blocStyle = $this->getBlocStyle($bloc);

                $content .= '<div class="page-bloc '. $bloc->type .'"'.$blocStyle.'>';

                switch($bloc->type) {

                    case 'text':
                        $content .= $bloc->content;
                    break;

                    case 'image':
                        $content.= '<img src="'. $this->imagesUrl . $bloc->content .'">';
                    break;

                    case 'file':
                        $content.= '<a href="'. $this->filesUrl . $bloc->content .'" target="_blank">'. $bloc->content .'</a>';
                    break;

                    case 'gallery':
                        
                        $gallery = $this->getGalleryDisplay($bloc->content);

                        $content .= '<div class="'. $gallery->display->class .'" data-type="'. $gallery->display->type .'" data-sub-type="'.$gallery->display->subtype.'" '.$gallery->display->height.'>';
                        $numPhoto = 0;
                        foreach($gallery->photos as $photo) {
                            $content .= $this->getPhotoRender($gallery, $photo, $numPhoto);
                            $numPhoto++;
                        }
                        $content .= '</div>';

                    break;

                    case 'code':
                        $content .= $bloc->content;
                    break;

                }

                $content .= '</div>';
            }
        }

        return $content;
    }



    /* ----------------- private utilities ----------------- */


    private function getGalleryDisplay($galleryId)
    {   
        $gallery = $this->galleriesyModel->getSingle($galleryId);

        /** Gallery display paramters */

        $type = $this->galleriesTypes[$gallery->type][0];
        $class = $this->galleriesTypes[$gallery->type][1];
        $subtype = null;
        @$subtype = $this->galleriesTypes[$gallery->type][2];
        $height = ($gallery->hauteur === '')? '' : ' style="height:'. $gallery->hauteur .'px"'; 
        $gallery->display = (object) [
            'type'      => $type,
            'subtype'   => $subtype,
            'class'     => $class,
            'height'    => $height
        ];

        /** Gallery photos display parameters */

        if($this->galleriesyPhotosModel->hasDatasPhotos($galleryId)) {
            $datasPhotos = $this->galleriesyPhotosModel->datasPhotos;
            $photos = [];
            foreach($datasPhotos as $datasPhoto) {
                $photos[] = (object) [
                    'id'        =>  $datasPhoto->id,
                    'filename'  =>  $datasPhoto->id . '.' . $this->photosType,
                    'title'     =>  $datasPhoto->titre,
                    'text'      =>  $datasPhoto->texte,
                    'link'      =>  $datasPhoto->lien,
                    'order'     =>  $datasPhoto->ordre
                ];                
            }
            $gallery->directory = $galleryId;
            $gallery->photos = $photos;
        }
        return $gallery;
    }



    private function getPhotoRender($gallery, $photo, $numPhoto)
    {
        $link = $this->getLink($photo->link, $gallery->display->subtype);
        $style = $this->getPhotoStyle($gallery, $numPhoto);
        $photosDirectory = $this->getPhotosDirectory($gallery);
        list($width, $height) = getimagesize($this->galleriesPath . $gallery->directory . $photosDirectory . $photo->filename);

        $content = '<div class="photo"'.$style.' data-gallery-id="'.$gallery->id.'" data-photo-filename="'.$photo->filename.'" data-photo-width="'.$width.'" data-photo-height="'.$height.'">';
        $content .= '<a href="'. $link->href .'"'. $link->target .'></a>';
        $content .= '<div>';
        $content .= '<h4>'. $photo->title .'</h4>';
        $content .= '<div>'. $photo->text .'</div>';
        $content .= '</div>';
        $content .= '<img src="'. $this->galleriesUrl . $gallery->directory . $photosDirectory . $photo->filename.'">';
        $content .= '</div>';

        return $content;
    }



    private function getBlocStyle($bloc)
    {
        $styles = [];
        $styles[] = 'margin-bottom:'.$bloc->marge.'px';
        $styles[] = ($bloc->type === 'text')? 'column-count:'.$bloc->colonnes.'"' : '';
        $blocStyle = ' style="'.implode(';', $styles).'"';
        return $blocStyle;
    }



    private function getPhotosDirectory($gallery)
    {        
        $photosDirectory = (intval($gallery->type) === 4)? '/medium/' : '/large/';
        $photosDirectory = (intval($gallery->colonnes) >= 4)? '/medium/' : '/large/';
        return $photosDirectory;
    }
    
    

    private function getPhotoStyle($gallery, $numPhoto)
    {
        $column = ($gallery->colonnes === '')? $this->columnsDefault : $gallery->colonnes;
        $margin = ($gallery->marges === '')? $this->marginDefault : $gallery->marges;
        $marginRight = ((($numPhoto + 1) % $column) == 0)? 0 : $margin;

        $style = '';
        if(in_array($gallery->type, [4, 5])) {
            $style = ' '
            .'style="'            
            .'width: calc((100% - ('.($column-1).' * '.$margin.'px)) / '.$column.'); '
            .'margin-right: '.$marginRight.'px; '
            .'margin-bottom: '.$margin.'px; '
            .'--num: '.$numPhoto.'"';
        }
        return $style;
    }



    private function getLink($photo_link, $type)
    {
        if(@strpos( $type, 'diaporama') !== false) {
            $href = '#';
            $target = '';
        }
        else{
            $href   =   $photo_link;
            $target =   ' target="_blank"';
            if(strpos($photo_link, 'http') === false) {
                $href       =   SITE_URL . $photo_link;
                $target     =   '';
            }
        }
        return (object) [
            'href'      => $href,
            'target'    => $target
        ];
    }



}