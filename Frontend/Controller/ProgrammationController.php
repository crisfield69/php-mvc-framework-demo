<?php

namespace Frontend\Controller;

use Frontend\Controller\Controller;
use Backend\Model\TranscriptionsModel;
use Backend\Model\ProgrammationModel;
use Core\HttpRequest;
use Frontend\PageManager;
use Core\Util;


class ProgrammationController extends Controller
{
    private $pageManager;
    private $programmationModel;
    private $transcriptionsModel;
    private $httpRequest;
    private $photosUrl = SITE_URL . 'uploads/programmation/';
    private $photosPath = SITE_PATH . 'uploads/programmation/';
    private $homePage = 'programmation-accueil';
    private $voidPage = 'programmation-vide';

    public function __construct()
    {
        parent::__construct();
        $this->pageManager = new PageManager();
        $this->programmationModel = new ProgrammationModel();
        $this->transcriptionsModel = new TranscriptionsModel();
        $this->httpRequest = new HttpRequest();      
    }
    
    public function getHome() : void
    {       
        
        $content = $this->pageManager->getContent($this->homePage);                
        $jours = $this->getDates();     

        $this->render('programmation-home', [
            'title'             =>  'Salon primevère - Programmation',
            'styles'            =>  'programmation-home',
            'content'           =>  $content,
            'jours'             =>  $jours,
            'type'              =>  $this->getPageType('programmation'),
            'wishlistWidget'    =>  $this->getWishlistWidgetRender()
        ]);
        
    }


    /* -------------- getAllBy... : Get all programs list by filter -------------- */

    public function getAllBySlug(string $type, string $categorySlug, object $programme=null)
    {
        $category = $this->transcriptionsModel->getMotsclefsBySlug($categorySlug);
        $selector  = (object) [
            'theme'     =>  '',
            'date'      =>  '',
            'lieu'      =>  '',
            'lieuParent' =>  '',
            'forme'     =>  '',
            'permanent' =>  false
        ];
        $selector->$type = $category;
        $programmes = $this->programmationModel->getProgammesByTheme($category, $categorySlug);
        $this->renderProgrammes($selector, $programmes, $programme);
    }
    
    public function getAllByTheme($themeSlug, $programme=null) : void
    { 
        $theme = $this->transcriptionsModel->getMotsclefsBySlug($themeSlug);
        $selector  = (object) [
            'theme'     =>  $theme,
            'date'      =>  '',
            'lieu'      =>  '',
            'lieuParent' =>  '',
            'forme'     =>  '',
            'permanent' =>  false
        ];
        $programmes = $this->programmationModel->getProgammesByTheme($theme, $themeSlug);
        $this->renderProgrammes($selector, $programmes, $programme);
    }

    public function getAllByLieu($lieuSlug, $programme=null) : void
    {  
        $lieu = $this->transcriptionsModel->getMotsclefsBySlug($lieuSlug);        
        $selector  = (object) [
            'theme' =>  '',
            'date'  =>  '',
            'lieu'  =>  $lieu,
            'lieuParent' =>  '',
            'forme' =>  '',
            'permanent' => false
        ];
        $programmes =  $this->programmationModel->getProgammesByLieu($lieu, $lieuSlug);
        $this->renderProgrammes($selector, $programmes, $programme);
    }

    public function getAllByLieuParent($lieuParentSlug, $programme=null) : void
    {  
        $lieuParent = $this->transcriptionsModel->getMotsclefsBySlug($lieuParentSlug);
        $selector  = (object) [
            'theme'         =>  '',
            'date'          =>  '',
            'lieu'          =>  '',
            'lieuParent'    =>  $lieuParent,
            'forme'         =>  '',
            'permanent'     => false
        ];
        $programmes =  $this->programmationModel->getProgammesByLieuParent($lieuParent, $lieuParentSlug);
        $this->renderProgrammes($selector, $programmes, $programme);
    }

    public function getAllByForme($formeSlug, $programme=null) : void
    {
        $forme = $this->transcriptionsModel->getMotsclefsBySlug($formeSlug);
        $selector  = (object) [
            'theme' =>  '',
            'date'  =>  '',
            'lieu'  =>  '',
            'lieuParent' =>  '',
            'forme' =>  $forme,
            'permanent' => false
        ];
        $programmes =  $this->programmationModel->getProgammesByForme($forme, $formeSlug);
        $this->renderProgrammes($selector, $programmes, $programme);
    }

    public function getAllByDate($date, $programme=null) : void
    {
        $selector  = (object) [
            'theme' =>  '',
            'date'  =>  $date,
            'lieu'  =>  '',
            'lieuParent' =>  '',
            'forme' =>  '',
            'permanent' => false
        ];
        $programmes = $this->programmationModel->getProgammesByDate($date);
        $this->renderProgrammes($selector, $programmes, $programme);
    }

    public function getAllByPermanent($programme=null) : void
    {
        $selector  = (object) [
            'theme' =>  '',
            'date'  =>  '',
            'lieu'  =>  '',
            'lieuParent' =>  '',
            'forme' =>  '',
            'permanent' => true
        ];
        $programmes =  $this->programmationModel->getPermanentsProgammes();
        $this->renderProgrammes($selector, $programmes, $programme);
    }



    /* -------------- getOneBy... : Get one programm detail by selector -------------- */

    public function getOneByTheme($themeSlug, $programmeSlug)
    {
        $theme = $this->transcriptionsModel->getMotsclefsBySlug($themeSlug);
        foreach($this->programmationModel->getProgammesByTheme($theme, $themeSlug) as $prog) {
            if(util::getSlug($prog->titre) === $programmeSlug) {
                $this->getAllByTheme($themeSlug, $prog);
                break;
            }
        }
    }

    public function getOneByLieu($lieuSlug, $programmeSlug)
    {
        $lieu = $this->transcriptionsModel->getMotsclefsBySlug($lieuSlug);
        foreach($this->programmationModel->getProgammesByLieu($lieu, $lieuSlug) as $prog) {
            if(util::getSlug($prog->titre) === $programmeSlug) {
                $this->getAllByLieu($lieuSlug, $prog);
                break;
            }
        }
    }

    public function getOneByLieuParent($lieuSlug, $programmeSlug)
    {
        $lieu = $this->transcriptionsModel->getMotsclefsBySlug($lieuSlug);
        foreach($this->programmationModel->getProgammesByLieuParent($lieu, $lieuSlug) as $prog) {
            if(util::getSlug($prog->titre) === $programmeSlug) {
                $this->getAllByLieuParent($lieuSlug, $prog);
                break;
            }
        }
    }

    public function getOneByForme($formeSlug, $programmeSlug)
    {
        $forme = $this->transcriptionsModel->getMotsclefsBySlug($formeSlug);
        foreach($this->programmationModel->getProgammesByForme($forme, $formeSlug) as $prog) {
            if(util::getSlug($prog->titre) === $programmeSlug) {
                $this->getAllByForme($formeSlug, $prog);
                break;
            }
        }
    }

    public function getOneByDate($dateSlug, $programmeSlug)
    {
        foreach($this->programmationModel->getProgammesByDate($dateSlug) as $prog) {
            if(util::getSlug($prog->titre) === $programmeSlug) {
                $this->getAllByDate($dateSlug, $prog);
                break;
            }
        }
    }

    public function getOneByPermanent($programmeSlug)
    {
        foreach($this->programmationModel->getPermanentsProgammes() as $prog) {
            if(util::getSlug($prog->titre) === $programmeSlug) {
                $this->getAllByPermanent($prog);
                break;
            }
        }
    }


    /* ------------------ private utilities ----------------- */ 


    /* -------------- getRender ... -------------- */

    private function renderProgrammes(object $selector, array $programmes, object $programme=null)
    {        
        $dates          =   $this->getDates($selector->date);
        $themes         =   $this->getThemes($selector->theme);
        //$lieux          =   $this->getLieux($selector->lieu);
        $lieuxParents   =   $this->getLieuxParents($selector->lieuParent);
        $formes         =   $this->getFormes($selector->forme);
        $permanents     =   $this->getPermanents($selector->permanent);
        $breadcrumb     =   $this->getBreadcrumRender();

        if($programme === null) {
            $wishType = null;
            $wishSlug = null;
        }
        else {
            $wishType = 'programme';
            $wishSlug = $programme->slug;
        }
        
        $wishlistWidget = $this->getWishlistWidgetRender($wishType, $wishSlug);

        $this->render('programmation', [
            'title'                     =>  'Salon primevère - Programmation',
            'styles'                    =>  'programmation',
            'type'                      =>  $this->getPageType('programmation'),
            'dates'                     =>  $dates,
            'themes'                    =>  $themes,
            //'lieux'                   =>  $lieux,
            'lieux'                     =>  $lieuxParents,
            'formes'                    =>  $formes,
            'permanents'                =>  $permanents,
            'programmes'                =>  $this->getProgrammesRender($programmes),
            'programme'                 =>  $this->getProgrammeRender($programme, $wishlistWidget),
            'breadcrumb'                =>  $breadcrumb,
            'wishlistWidget'            =>  $wishlistWidget
        ]);
    }

    private function getBreadcrumRender()
    {
        $currentUrl = $this->httpRequest->currentUrl;
        $breadcrumb = '';
        $partsUrl = explode('/', $currentUrl);

        if(isset($partsUrl[1])) $categorySlug = $partsUrl[1];
        if(isset($partsUrl[2])) $categoryValueSlug = $partsUrl[2];
        if(isset($partsUrl[3])) $programmeSlug = $partsUrl[3];

        if(isset($categorySlug)) {
            $breadcrumb .= ''
            .'<li>'
            .'<a href="'.SITE_URL.'programmation">'
            .'Programmation'
            .'</a>'
            .'</li>';
        }

        if(isset($categoryValueSlug)) {
            $breadcrumb .= ''
            .'<li>'
            .'<a href="'.SITE_URL.'programmation/'.$categorySlug.'/'.$categoryValueSlug.'">';
            if($categorySlug === 'date'){
                $libelle = Util::dateToFrench($categoryValueSlug, 'l j F Y');
            }
            else{
                $libelle = $this->transcriptionsModel->getLibelleBySlug($categoryValueSlug);
            }
            $breadcrumb .= ''
            .$libelle
            .'</a>'
            .'</li>';
        }

        if(isset($programmeSlug)) {
            $breadcrumb .= ''
            .'<li>'
            .'<a href="'.SITE_URL.'programmation/'.$categorySlug.'/'.$categoryValueSlug.'/'.$programmeSlug.'">'
            .$this->programmationModel->getTitreLibelleBySlug($programmeSlug)
            .'</a>'
            .'</li>';
        }

        $breadcrumb .= '</ul>';


        if(isset($categorySlug) && $categorySlug === 'permanents') {
            $breadcrumb = ''
            .'<li>'
            .'<a href="'.SITE_URL.'programmation">'
            .'Programmation'
            .'</a>'
            .'</li>'
            .'<li>'
            .'<a href="'.SITE_URL.'programmation/permanents">'
            .'Permanents'
            .'</a>'
            .'</li>';
            
            if(isset($categoryValueSlug)) {
                $programmeSlug = $categoryValueSlug;
                $breadcrumb .= ''
                .'<li>'
                .'<a href="'.SITE_URL.'programmation/permanents/'.$programmeSlug.'">'
                .$this->programmationModel->getTitreLibelleBySlug($programmeSlug)
                .'</a>'
                .'</li>';
            }
        }
        return $breadcrumb;
    }

    private function getProgrammesRender($programmes = null)
    {
        if($programmes !== null && count($programmes)>0) { 
            $programmes = $this->getRender(['programmation', 'programmes'], [
                'categorie'     =>  '',
                'programmes'    =>  $programmes
            ]);
            return $programmes;
        }
        return null;
    }

    private function getProgrammeRender(?object $programme,  $wishlistWidgetRender)
    {
        if($programme !== null) {
            if(!empty($programme->photo) && is_file($this->photosPath . $programme->photo)) {
                $programme->photo = '<img src="'.$this->photosUrl . $programme->photo.'">';
            }
            $programme = $this->getRender(['programmation', 'programme'], [
                'programme'         =>  $programme,
                'wishlistWidget'    =>  $wishlistWidgetRender,
            ]);
            return $programme;
        }
        else {
            $void = $this->getVoid();
            $programme = $this->getRender(['programmation', 'void'], [
                'content' =>  $void,
            ]);
            return $programme;
        }
        return null;
    }

    private function getVoid()
    {
        return $this->pageManager->getContent($this->voidPage);
    }

    
    /* -------------- getCategories lists -------------- */


    private function getThemes(string $theme='') : array
    {        
        $themes = [];
        $transcriptions = $this->transcriptionsModel->getAllByOrdre();
        $themesMotsClefs = $this->programmationModel->getThemes();
        
        foreach($transcriptions as $transcription) {

            if($transcription->type === 'theme' && in_array($transcription->motsclefs, $themesMotsClefs)) {
                $selected = ($transcription->motsclefs === $theme)? 'class="selected" ' : '';
                $themes[] = (object) [
                    'lien'      =>  SITE_URL . 'programmation/theme/' . $transcription->slug,
                    'libelle'   =>  $transcription->libelle,
                    'slug'      =>  $transcription->slug,
                    'selected'  =>  $selected
                ];
            }
        }
       
        return $themes;
    }

    private function getLieux(string $lieu='') : array
    {   

        $lieux = [];
        $transcriptions = $this->transcriptionsModel->getAllByOrdre();
        foreach($transcriptions as $transcription) {
            $selected = ($transcription->motsclefs === $lieu)? 'class="selected" ' : '';
            if(in_array($transcription->motsclefs, $this->programmationModel->getLieux())) {
                $lieux[] = (object) [
                    'lien'      =>  SITE_URL . 'programmation/lieu/' . $transcription->slug,
                    'libelle'   =>  $transcription->libelle,
                    'slug'      =>  $transcription->slug,
                    'selected'  =>  $selected
                ];
            }
        }
        return $lieux;
    }

    private function getLieuxParents(string $lieuParent='') : array
    {        
        $lieuxParents = [];
        $transcriptions = $this->transcriptionsModel->getAllByOrdre();       
        foreach($transcriptions as $transcription) {

            $selected = ($transcription->motsclefs === $lieuParent)? 'class="selected" ' : '';
            if(in_array($transcription->motsclefs, $this->programmationModel->getLieuxParents())) {
                $lieuxParents[] = (object) [
                    'lien'      =>  SITE_URL . 'programmation/lieu/' . $transcription->slug,
                    'libelle'   =>  $transcription->libelle,
                    'slug'      =>  $transcription->slug,
                    'selected'  =>  $selected,
                ];
            }
        }
        return $lieuxParents;
    }

    private function getFormes(string $forme='') : array
    {
        $formes = [];
        $transcriptions = $this->transcriptionsModel->getAllByOrdre();
        foreach($transcriptions as $transcription) {
            $selected = ($transcription->motsclefs === $forme)? 'class="selected" ' : '';
            if(in_array($transcription->motsclefs, $this->programmationModel->getFormes())) {
                $formes[] = (object) [
                    'lien'      =>  SITE_URL . 'programmation/forme/' . $transcription->slug,
                    'libelle'   =>  $transcription->libelle,
                    'slug'      =>  $transcription->slug,
                    'selected'  =>  $selected
                ];
            }
        }
        return $formes;
    }

    private function getDates(string $_date='') : array
    {
        $dates= [];
        foreach($this->programmationModel->getDates() as $date) {
            $selected = ($date === $_date)? 'class="selected" ' : '';
            $dates[] = (object) [
                'lien'      =>  SITE_URL . 'programmation/date/'. str_replace( ' ', '-', $date),
                'libelle'   =>  Util::dateToFrench($date, 'l j F Y'),
                'slug'      =>  str_replace( ' ', '-', $date),
                'selected'  =>  $selected
            ];
        }
        return $dates;
    }

    private function getPermanents(bool $permanent=false) : object
    {
        $selected = ($permanent === true)? 'class="selected" ' : '';
        $permanents = (object) [
            'lien'      =>  SITE_URL . 'programmation/permanents',
            'libelle'   =>  'Permanents',
            'slug'      =>  'permanents',
            'selected'  =>  $selected            
        ];
        return $permanents;
    }

}