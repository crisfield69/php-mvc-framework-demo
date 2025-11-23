<?php

namespace Backend\Controller;

use Backend\Controller\Controller;
use Backend\Model\TranscriptionsModel;
use Backend\Model\ProgrammationModel;
use Core\Util;

class TranscriptionsController extends Controller
{
    private $transcriptionsModel;
    private $programmationModel;
    

    public function __construct()
    {
        parent::__construct();
        $this->programmationModel = new ProgrammationModel();
        $this->transcriptionsModel = new TranscriptionsModel();
    }


    public function getHome() : void
    {   
        $themes = $this->getThemes();        
        $formes = $this->getFormes();
        $lieux = $this->getLieux();
        $lieuxParents  = $this->getLieuxParents();

        $this->render('transcriptions', [

            'title'     => 'Administration - Programmation',
            'styles'    => 'admin',
            'themes'    => $themes,
            'formes'    => $formes,
            'lieux'     => $lieux,
            'lieuxParents' => $lieuxParents
            
        ]);
    }


    public function postHome() : void
    {
        foreach($this->httpRequest->post as $key=>$value) {
            $$key = $value;
        }
        for($i=0; $i<count($motsclefs); $i++) {             

            $transcription = $this->transcriptionsModel->hasId($ids[$i]);
            
            if($transcription === false) {
                $slug = (!empty($libelles[$i]))? util::getSlug($libelles[$i]) : '';
                $this->transcriptionsModel->insert([
                    'libelle'   =>  [$libelles[$i], 's'],
                    'motsclefs' =>  [$motsclefs[$i], 's'],
                    'type'      =>  [$types[$i], 's'],
                    'slug'      =>  [$slug, 's']                     
                ]);
            }

            else {                
                $slug = (!empty($libelles[$i]))? util::getSlug($libelles[$i]) : '';                
                $this->transcriptionsModel->update([
                    'libelle'   =>  [$libelles[$i], 's'],
                    'motsclefs' =>  [$motsclefs[$i], 's'],
                    'type'      =>  [$types[$i], 's'],
                    'slug'      =>  [$slug, 's'] 

                ], 'id='. intval($ids[$i]));               
            }

        }
        $this->gotoUrl('admin/transcriptions');
    }


    private function getThemes() : array
    {
        $themes = [];
        foreach($this->programmationModel->getThemes() as $theme) {

            /*
            $libelle = $this->getLibelleByMotsclefs($theme);
            $id = $this->getIdByMotsclefs($theme);
            $slug = $this->getSlugByMotsClefs($theme);
            */

            $libelle = $this->getLibelleByMotclefAndType($theme, 'theme');
            $id = $this->getIdByMotclefAndType($theme, 'theme');
            $slug = $this->getSlugByMotClefAndType($theme, 'theme');

            $isComplete = empty($libelle)? 'uncomplete' : 'complete';            
            $themes[] = (object) [
                'motsclefs' =>  $theme,
                'libelle'   =>  $libelle,
                'type'      =>  'theme',
                'id'        =>  $id,
                'slug'      =>  $slug,
                'complete'   => $isComplete
            ];
        }
        return $themes;
    }


    private function getFormes() : array
    {
        $formes = [];
        foreach($this->programmationModel->getFormes() as $forme) {
            
            /*
            $libelle = $this->getLibelleByMotsclefs($forme);
            $id = $this->getIdByMotsclefs($forme);
            $slug = $this->getSlugByMotsClefs($forme);
            */

            $libelle = $this->getLibelleByMotclefAndType($forme, 'forme');
            $id = $this->getIdByMotclefAndType($forme, 'forme');
            $slug = $this->getSlugByMotClefAndType($forme, 'forme');

            $isComplete = empty($libelle)? 'uncomplete' : 'complete';      
            $formes[] = (object) [
                'motsclefs' =>  $forme,
                'libelle'   =>  $libelle,
                'type'      =>  'forme',
                'id'        =>  $id,
                'slug'      =>  $slug,
                'complete'  =>  $isComplete
            ];
        }
        return $formes;
    }


    private function getLieux() : array
    {
        $lieux = [];
        foreach($this->programmationModel->getLieux() as $lieu) {

            /*
            $libelle = $this->getLibelleByMotsclefs($lieu);
            $id = $this->getIdByMotsclefs($lieu);
            $slug = $this->getSlugByMotsClefs($lieu);
            */

            $libelle = $this->getLibelleByMotclefAndType($lieu, 'lieu');
            $id = $this->getIdByMotclefAndType($lieu, 'lieu');
            $slug = $this->getSlugByMotClefAndType($lieu, 'lieu');

            $isComplete = empty($libelle)? 'uncomplete' : 'complete';      
            $lieux[] = (object) [
                'motsclefs' =>  $lieu,
                'libelle'   =>  $libelle,
                'type'      =>  'lieu',
                'id'        =>  $id,
                'slug'      =>  $slug,
                'complete'  =>  $isComplete
            ];
        }
        return $lieux;
    }


    private function getLieuxParents() : array
    {
       $lieuxParents = [];
        foreach($this->programmationModel->getLieuxParents() as $lieu) {

            /*
            $libelle = $this->getLibelleByMotsclefs($lieu);
            $id = $this->getIdByMotsclefs($lieu);
            $slug = $this->getSlugByMotsClefs($lieu);
            */

            $libelle = $this->getLibelleByMotclefAndType($lieu, 'lieuparent');
            $id = $this->getIdByMotclefAndType($lieu, 'lieuparent');
            $slug = $this->getSlugByMotClefAndType($lieu, 'lieuparent');

            $isComplete = empty($libelle)? 'uncomplete' : 'complete'; 
            $lieuxParents[] = (object) [
                'motsclefs' =>  $lieu,
                'libelle'   =>  $libelle,
                'type'      =>  'lieuparent',
                'id'        =>  $id,
                'slug'      =>  $slug,
                'complete'  =>  $isComplete
            ];
        }
        return$lieuxParents;
    }


    private function getSlugByMotsClefs($motsclefs)
    {
        $slug = [
            'libelle'   =>  '',
            'value'     =>  ''
        ];
        $transcription = $this->transcriptionsModel->hasMotsclefs($motsclefs);
        if($transcription !== false) {

            if(!empty($transcription->slug)) {
                $slug = [
                    'libelle' => $transcription->slug,
                    'value' => $transcription->slug
                ];
            }
            else {
                $slug = [
                    'libelle' => util::getSlug($transcription->libelle),
                    'value' => util::getSlug($transcription->libelle)
                ];
            }
        }
        return (object) $slug;
    }


    private function getSlugByMotClefAndType($motclef, $type)
    {   
        $slug = [
            'libelle'   =>  '',
            'value'     =>  ''
        ];
        $transcription = $this->transcriptionsModel->select('motsclefs='.Util::escape($motclef).' AND type='.Util::escape($type), true);
        if($transcription === false || empty($transcription)) {    
        }
        else {            
            if(empty($transcription->slug)) {
                $slug = [
                    'libelle' => util::getSlug($transcription->libelle),
                    'value' => util::getSlug($transcription->libelle)
                ];
            }
            else {
                $slug = [
                    'libelle' => $transcription->slug,
                    'value' => $transcription->slug
                ];
            }
        }
        return (object) $slug;
    }


    private function getLibelleByMotsclefs($motsclefs)
    {
        $libelle = '';
        $transcription = $this->transcriptionsModel->hasMotsclefs($motsclefs);
        if($transcription !== false) {
            $libelle = $transcription->libelle;
        }
        return $libelle;
    }


    private function getLibelleByMotclefAndType($motclef, $type)
    {
        $libelle = '';
        $transcription = $this->transcriptionsModel->select('motsclefs='.Util::escape($motclef).' AND type='.Util::escape($type), true);
        if($transcription === false || empty($transcription)) {              
        }
        else {
            $libelle = $transcription->libelle;
        }                
        return $libelle;
    }

    
    private function getIdByMotsclefs($motsclefs)
    {
        $id = '';
        $transcription = $this->transcriptionsModel->hasMotsclefs($motsclefs);
        if($transcription !== false) {
            $id = $transcription->id;
        }
        return $id;
    } 


    private function getIdByMotclefAndType($motclef, $type)
    {
        $id = '';        
        $transcription = $this->transcriptionsModel->select('motsclefs='.Util::escape($motclef).' AND type='.Util::escape($type), true);
        if($transcription === false || empty($transcription)) {              
        }
        else {
            $id = $transcription->id;
        }
        return $id;
    } 
      

}



