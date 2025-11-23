<?php

namespace Backend\Controller;

use Backend\Controller\Controller;
use Backend\Model\ConferencesModel;
use Core\PhotosManager;
use Core\Util;

class ConferencesController extends Controller
{

    private $audioFilesPath = SITE_PATH . 'uploads/conferences/mp3/';
    private $photosFilesPath = SITE_PATH . 'uploads/conferences/photos/';
    private $photosFilesUrl = SITE_URL . 'uploads/conferences/photos/';
    private $conferencesModel;
    private $photoManager;


    public function __construct()
    {
        parent::__construct();
        $this->conferencesModel = new ConferencesModel();
        $this->photoManager = new PhotosManager();
    }



    public function getHome()
    {
        $conferences = [];
        $inpuFileId = 0;        

        if ($opendir = opendir($this->audioFilesPath)) {
            while (false !== ($file = readdir($opendir))) {
                if (is_file($this->audioFilesPath.$file) && $file != "." && $file != "..") {

                    $conference = $this->conferencesModel->getConfernceByFileName($file);

                    if(empty($conference)) {
                        
                        $conference = (object) [
                            'fichier'       => $file,
                            'titre'         => '',
                            'soustitre'     => '',
                            'photo'         => '',
                            'id'            => '',
                            'inputfileId'   => $inpuFileId,
                            'horsligne'     => '',
                            'widgetOrdre'   => null,
                            'ordre'         => null
                        ];
                    }

                    else {

                        $photo = '';
                        if($this->photoManager->photoExist($this->photosFilesPath . 'small/' . $conference->id . '.' . $this->photoManager->photosType)) {
                            $photo = $this->photosFilesUrl . 'small/' . $conference->id . '.' . $this->photoManager->photosType . '?' . rand();
                        }

                        $horsligne = '';
                        if(intval($conference->horsligne) === 1) $horsligne = ' checked';

                        $widgetOrdre = $this->getRender(['conferences-ordres'], [
                            'conference'    => $conference,
                            'ordres'        => $this->conferencesModel->getAllOrdres()
                        ]);

                        $conference = (object) [
                            'fichier'       => $file,
                            'titre'         => $conference->titre,
                            'soustitre'     => $conference->soustitre,
                            'photo'         => $photo,
                            'id'            => $conference->id,
                            'inputfileId'   => $inpuFileId,
                            'horsligne'     =>  $horsligne,
                            'widgetOrdre'   => $widgetOrdre,
                            'ordre'         => $conference->ordre
                        ];
                    }

                    $conferences[] = $conference;

                }
                $inpuFileId++;
            }
            closedir($opendir);
        }

        usort($conferences,function($first,$second){
            if ($first->ordre===$second->ordre) {
                return 0;
            }
            return ($first->ordre>$second->ordre)? 1 : -1;
        });

        $this->render('conferences', [
            'title'         =>  'Administration - Categories',
            'styles'        =>  'admin',
            'conferences'   =>  $conferences
        ]);

    }



    public function postHome()
    {  
        foreach($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }
        
        $photos = util::normalizeUploadedFiles($this->httpRequest->files->photos);

        for($i=0; $i<count($titres); $i++) {
            
            $fichier = $fichiers[$i];
            $titre = $titres[$i];
            $soustitre = $soustitres[$i];
            
            $horsligne = 0;
            if(isset($horslignes))
                $horsligne = (in_array($fichier, $horslignes))? 1 : 0;

            $photo = (isset($photos[$i]) && !empty($photos[$i]))? $photos[$i] : null;
            
            $conference = $this->conferencesModel->getConfernceByFileName($fichier);

            if(empty($conference)) {
                $this->conferencesModel->insert([
                    'fichier'   => [$fichier, 's'],
                    'titre'     => [$titre, 's'],
                    'soustitre' => [$soustitre, 's'],
                    'horsligne' => [$horsligne, 'i'],
                    'ordre'      =>  [$i+1, 'i']
                ]);
                $id = $this->conferencesModel->lastId();
            }
            else {
                $id = $conference->id;
                $ordre = (empty($conference->ordre))? $ordre = $i+1 : $conference->ordre;
                $this->conferencesModel->update([ 
                    'titre'     => [$titre, 's'],
                    'soustitre' => [$soustitre, 's'],
                    'horsligne' => [$horsligne, 'i'],
                    'ordre'     =>  [$ordre, 'i']
                ], 'id='.intval($id));
            }

            if($photo !== null) {
                $this->photoManager->addPhoto($photo, $this->photosFilesPath, '', $id);
            }
            
        }
        $this->deleteVoids();
        $this->gotoUrl('admin/conferences');
        
    }

    public function postUpdateOrder()
    {
        foreach($this->httpRequest->post as $key => $value) {
            $$key = $value;
        }
        $this->conferencesModel->updateOrder($currentOrder, $newOrder);
        $response =  json_encode([
            'result'    => 'success'
        ]);
        echo $response;
    }

    private function deleteVoids()
    {
        $conferences = $this->conferencesModel->getAllOrderBy('ordre');
        foreach($conferences as $conference) {
            if(!is_file($this->audioFilesPath.$conference->fichier)) {
                $this->conferencesModel->delete('id='.intval($conference->id));
            }
        }
        $ordre = 1;
        $conferences = $this->conferencesModel->getAllOrderBy('ordre');
        foreach($conferences as $conference) {
            $this->conferencesModel->update([
                'ordre' => [$ordre, 'i'],
                'temp'  => [0, 'i']
            ], 'id='.intval($conference->id));
            $ordre++;
        }
            
    }
    


}