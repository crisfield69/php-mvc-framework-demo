<?php

namespace Frontend\Controller;

use Core\HttpRequest;
use Core\Util;
use Backend\Model\ProgrammationModel;
use Frontend\Model\ExposantsModel;


class SearchController extends Controller 
{
   
    private $htttpRequest;
    private $exposantsModel;
    private $programmationModel;

    public function __construct()
    {
        parent::__construct();
        $this->htttpRequest = new HttpRequest();
        $this->exposantsModel = new ExposantsModel();
        $this->programmationModel = new ProgrammationModel();
    }

    public function postSearch()
    {   
        $resultats = [];      
        $search = $this->htttpRequest->post->search;
        $nb_resultats = 0;

        $result = $this->exposantsModel->pureQuery('SELECT * FROM exposants WHERE '
        .'name LIKE '.Util::escape('%'.$search.'%').' OR '
        .'stand_name LIKE '.Util::escape('%'.$search.'%').' OR '
        .'website_introduction LIKE '.Util::escape('%'.$search.'%').' OR '
        .'services_informations LIKE '.Util::escape('%'.$search.'%').' OR '
        .'products_informations LIKE '.Util::escape('%'.$search.'%')
        );
        $resultats['exposants'] = [];

        while($exposant = mysqli_fetch_object($result)) {
            $exposant->url = $this->getUrlByExposant($exposant);
            $resultats['exposants'][] = $exposant;
            $nb_resultats++;
        }

        $result = $this->programmationModel->pureQuery('SELECT * FROM programmation WHERE '
        .'titre LIKE '.Util::escape('%'.$search.'%').' OR '
        .'texte LIKE '.Util::escape('%'.$search.'%').' OR '
        .'intervenant_long LIKE '.Util::escape('%'.$search.'%')
        );
        $resultats['programmation'] = [];
        $programmation_ids = [];

        while($programmation = mysqli_fetch_object($result)) {
            if(!in_array($programmation->id, $programmation_ids)) {
                $category = (!empty($programmation->theme))? 'theme' : 'lieu';
                $programmation->url = $this->getUrlByProgrammation($programmation, $category);
                $programmation->texte = mb_substr($programmation->texte, 0, 100, 'UTF-8').' ...';
                $resultats['programmation'][] = $programmation;
                $programmation_ids[] = $programmation->id;
                $nb_resultats++;
            }
        }

        $this->render('search', [
            'styles'            =>  'search',
            'title'             =>  'Résultats de recherche',
            'wishlistWidget'    =>  null,
            'recherche'         =>  $search,
            'resultats'         =>  $resultats,
            'nb_resultats'      =>  $nb_resultats,
            'capitalize'        =>  function ($string) {
                return $this->getCapitalize($string);
            },
            'writeclean' => function ($string, $value) {
                return $this->writeClean($string, $value);
            }            
        ]);

    }
    
    
    private function getUrlByProgrammation($programmation, $category)
    {
        if($category == 'theme') {
            $theme = trim($programmation->theme, ',,');
            $theme = explode(',,', $theme);
            $theme = array_shift($theme);
            $theme = Util::getSlug($theme);
            return SITE_URL . 'programmation/'
            . 'theme' . '/'
            . $theme . '/'
            . Util::getSlug($programmation->titre);
        }

        if($category == 'lieu') {
            $lieux_parent = trim($programmation->lieux_parent, ',');
            $lieux_parent = Util::getSlug($lieux_parent);
            return SITE_URL . 'programmation/'
            . 'lieu' . '/'
            . $lieux_parent . '/'
            . Util::getSlug($programmation->titre);
        }        
    }


    private function getUrlByExposant($exposant)
    {
        return SITE_URL . 'exposants/' 
        . util::getSlug($exposant->activity_sector) . '/' 
        . Util::getSlug($exposant->specialties) . '/'
        . Util::getSlug($exposant->name);
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
    

}