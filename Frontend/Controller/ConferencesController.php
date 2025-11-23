<?php

namespace Frontend\Controller;

use Frontend\Controller\Controller;
use Backend\Model\ConferencesModel;
use Core\PhotosManager;


class ConferencesController extends Controller
{
    private $conferencesModel;
    private $photosUrl = SITE_URL . 'uploads/conferences/photos/medium/';
    private $photosPath = SITE_PATH . 'uploads/conferences/photos/medium/';
    
    private $mp3sPath = SITE_PATH . 'uploads/conferences/mp3/';
    private $mp3ssUrl = SITE_URL . 'uploads/conferences/mp3/';

    private $defaultPhotoUrl = SITE_URL . 'uploads/conferences/default-photo.jpg';
    private $photosManager; 


    public function __construct()
    {
        parent::__construct();
        $this->conferencesModel = new ConferencesModel();
        $this->photosManager = new PhotosManager();
    }



    public function getHome()
    {
        //$conferences = $this->conferencesModel->getAllOrderBy('ordre');
        $conferences = $this->conferencesModel->select('horsligne=0 ORDER BY ordre');

        foreach($conferences as $conference) {            
            $conference->photo = $this->getRenderPhoto($conference);
            $conference->audio = $this->getRenderAudio($conference);
        }

        $this->render('conferences', [
            'styles'            =>  'conferences',
            'type'              =>  $this->getPageType('conferences'),
            'title'             =>  'Conferences',
            'conferences'       =>  $conferences,
            'wishlistWidget'    =>  $this->getWishlistWidgetRender()
        ]);

    }



    private function getRenderPhoto($conference)
    {
        $photoPath = $this->photosPath . $conference->id . '.' . $this->photosManager->photosType;
        $photoUrl = $this->photosUrl . $conference->id . '.' . $this->photosManager->photosType;

        if($this->photosManager->photoExist($photoPath)) {
            $url = $photoUrl;
        }
        else{
            $url = $this->defaultPhotoUrl;
        }
        return '<img src="'.$url.'" alt="">';
    }



    private function getRenderAudio($conference) 
    {
        $audioPath = $this->mp3sPath . $conference->fichier;
        $audioUrl = $this->mp3ssUrl . $conference->fichier;

        if(is_file($audioPath)) {
            return $this->getRender(['conferences', 'audio'], [
                'src'   => $audioUrl,
                'type'  => $this->getAudioType($audioPath)
            ]);
        }
        else{
            return '';
        }
    }



    public function getAudioType( $audioPath ) 
    {
        $allowedTypes = [
            'audio/mpeg', 
            'audio/x-mpeg', 
            'audio/mpeg3', 
            'audio/x-mpeg-3', 
            'audio/aiff', 
            'audio/mid', 
            'audio/x-aiff', 
            'audio/x-mpequrl',
            'audio/midi', 
            'audio/x-mid', 
            'audio/x-midi',
            'audio/wav',
            'audio/x-wav',
            'audio/xm',
            'audio/x-aac',
            'audio/basic',
            'audio/flac',
            'audio/mp4',
            'audio/x-matroska',
            'audio/ogg',
            'audio/s3m',
            'audio/x-ms-wax',
            'audio/xm'
        ];

        
        $finfo = finfo_open( FILEINFO_MIME_TYPE );
        $type = finfo_file( $finfo, $audioPath );
        finfo_close( $finfo );
        
        if(in_array($type, $allowedTypes)) {
            return $type;
        }        
        
    }
}