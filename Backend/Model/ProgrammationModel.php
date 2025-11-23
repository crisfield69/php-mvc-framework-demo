<?php

namespace Backend\Model;
use Core\Model;
use Core\Util;
use Backend\Model\TranscriptionsModel;


class ProgrammationModel extends Model
{
    protected $table = 'programmation';
    protected $programmes;
    protected $transcriptionsModel;

    public function __construct()
    {
        parent::__construct();
        $this->transcriptionsModel = new TranscriptionsModel();
        $this->programmes = $this->getAll();
    }

    
    public function getThemes() : array
    {
        $themes = [];
        foreach($this->programmes as $programme) {
            $array = explode(',,', trim($programme->theme, ','));
            foreach($array as $item) {
                if($item && !in_array($item, $themes)) {
                    $themes[] = $item;
                }
            }
        }
        return $themes;
    }


    public function getFormes() : array
    {
        $formes = [];
        foreach($this->programmes as $programme) {
            $forme = $programme->forme;
            if($forme && !in_array($forme, $formes)) {
                //if(!$programme->permanent)
                    $formes[] = $forme;
            }
        }
        return $formes;
    }


    public function getLieux() : array
    {
        $lieux = [];
        foreach($this->programmes as $programme) { 
            $lieu = trim($programme->lieux, ',');
            if($lieu && !in_array($lieu, $lieux)) {
                //if(!$programme->permanent)
                    $lieux[] = $lieu;
            }
        }
        return $lieux;
    }


    public function getLieuxParents()
    {   
        $lieuxParents = [];
        foreach($this->programmes as $programme) { 
            $lieu = trim($programme->lieux_parent, ',');
            if($lieu && !in_array($lieu, $lieuxParents)) {                
                $lieuxParents[] = $lieu;
            }
        }
        return $lieuxParents;        
    }


    public function getDates() : array
    {
        $dates = [];
        foreach($this->programmes as $programme) {
            $date = $programme->date;
            if($date && !in_array($date, $dates)) {
                if(!$programme->permanent)
                    $dates[] = $date;
            }
        }
        return $dates;
    }

    
    public function getPermanents() :array
    {
        $permanents = [];
        foreach($this->programmes as $programme) {
            if($programme->permanent)
                $permanents[] = $programme;
        }
        return $permanents;
    }


    public function getProgammesByTheme(string $theme, string $themeSlug) : array
    {

        $programmes = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE theme LIKE '.Util::escape('%'.$theme.'%').' ORDER BY date, heure');

        while($programme = mysqli_fetch_object($result)) {

            $programme->date = $this->getDateToFrenchIfNotPermanent($programme->date);
            $programme->heure = $this->getHeureFormat($programme->heure);
            $programme->duree = $this->getHeureFormat($programme->duree);
            $programme->slug = Util::getSlug($programme->titre);
            $programme->url = SITE_URL . 'programmation/theme/'.$themeSlug.'/'.$programme->slug;
            $programme->horraires = $this->getHeuresById($programme->id);            
            $programme->forme = $this->transcriptionsModel->getLibelleByMotsclefs($programme->forme);
            $programme->lieux = $this->getlieuxById($programme->id);
            $programmes[] = $programme;

        }
        return $programmes;
    }


    public function getProgammesByDate(string $date) : array
    {
        $programmes = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE date LIKE '.Util::escape('%'.$date.'%').' AND permanent=0 ORDER BY date, heure');
        
        while($programme = mysqli_fetch_object($result)) {
            
            $programme->date = $this->getDateToFrenchIfNotPermanent($programme->date);
            $programme->heure = $this->getHeureFormat($programme->heure);
            $programme->duree = $this->getHeureFormat($programme->duree);
            $programme->slug = Util::getSlug($programme->titre);
            $programme->url = SITE_URL . 'programmation/date/'.$date.'/'.$programme->slug;
            $programme->horraires = $this->getHeuresById($programme->id);
            $programme->forme = $this->transcriptionsModel->getLibelleByMotsclefs($programme->forme);
            $programme->lieux = $this->getlieuxById($programme->id);
            $programmes[] = $programme;

        }
        return $programmes;
    }


    public function getProgammesByLieu(string $lieu, string $lieuSlug) : array
    {        
        $programmes = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE lieux LIKE '.Util::escape('%'.$lieu.'%').' ORDER BY date, heure');
        
        while($programme = mysqli_fetch_object($result)) {            

            $programme->date = $this->getDateToFrenchIfNotPermanent($programme->date);            
            $programme->heure = $this->getHeureFormat($programme->heure);
            $programme->duree = $this->getHeureFormat($programme->duree);
            $programme->slug = Util::getSlug($programme->titre);
            $programme->url = SITE_URL . 'programmation/lieu/'.$lieuSlug.'/'.$programme->slug;
            $programme->horraires = $this->getHeuresById($programme->id);
            $programme->forme = $this->transcriptionsModel->getLibelleByMotsclefs($programme->forme);
            $programme->lieux = $this->getlieuxById($programme->id);
            $programmes[] = $programme;
         
        }
        return $programmes;
    }


    public function getProgammesByLieuParent(string $lieu, string $lieuSlug) : array
    {        
        $programmes = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE lieux_parent LIKE '.Util::escape('%'.$lieu.'%').' ORDER BY date, heure');
        
        while($programme = mysqli_fetch_object($result)) {            

            $programme->date = $this->getDateToFrenchIfNotPermanent($programme->date);            
            $programme->heure = $this->getHeureFormat($programme->heure);
            $programme->duree = $this->getHeureFormat($programme->duree);
            $programme->slug = Util::getSlug($programme->titre);
            $programme->url = SITE_URL . 'programmation/lieu/'.$lieuSlug.'/'.$programme->slug;
            $programme->horraires = $this->getHeuresById($programme->id);
            $programme->forme = $this->transcriptionsModel->getLibelleByMotsclefs($programme->forme);
            $programme->lieux = $this->getlieuxById($programme->id);
            $programmes[] = $programme;
         
        }
        return $programmes;
    }


    public function getProgammesByForme(string $forme, string $formeSlug) : array
    {
        $programmes = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE forme LIKE '.Util::escape('%'.$forme.'%').' ORDER BY date, heure');
        
        while($programme = mysqli_fetch_object($result)) {
            
            $programme->date = $this->getDateToFrenchIfNotPermanent($programme->date);
            $programme->heure = $this->getHeureFormat($programme->heure);
            $programme->duree = $this->getHeureFormat($programme->duree);
            $programme->slug = Util::getSlug($programme->titre);
            $programme->url = SITE_URL . 'programmation/forme/'.$formeSlug.'/'.$programme->slug;
            $programme->horraires = $this->getHeuresById($programme->id);
            $programme->forme = $this->transcriptionsModel->getLibelleByMotsclefs($programme->forme);
            $programme->lieux = $this->getlieuxById($programme->id);
            $programmes[] = $programme;

        }
        return $programmes;
    }

    public function getPermanentsProgammes() : array
    {
        $programmes = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE permanent=1 ORDER BY date, heure');
        
        while($programme = mysqli_fetch_object($result)) {        
            $programme->date = $this->getDateToFrenchIfNotPermanent($programme->date);
            $programme->heure = $this->getHeureFormat($programme->heure);
            $programme->duree = $this->getHeureFormat($programme->duree);
            $programme->slug = Util::getSlug($programme->titre);
            $programme->url = SITE_URL . 'programmation/permanents/'.$programme->slug;
            $programme->horraires = $this->getHeuresById($programme->id);
            $programme->forme = $this->transcriptionsModel->getLibelleByMotsclefs($programme->forme);
            $programme->lieux = $this->getlieuxById($programme->id);
            $programmes[] = $programme;

        }
        return $programmes;
    }


    public function getHeureFormat($dateTime=null)
    {
        if($dateTime && $dateTime !== '00:00:00') {
            $hour = new \DateTime($dateTime);
            return $hour->format('H\hi');
        }
        else{
            return '';
        }        
    }


    public function getHeuresById($id)
    {
        $programmes = [];
        foreach($this->select('id='.intval($id).' ORDER BY date') as $programme) {
            $dateToFrench = null;
            $time = null;
            
            if( $programme->date !== '0000-00-00') {
                $dateToFrench = Util::dateToFrench($programme->date, 'l j F Y');
            }
            if($programme->heure != '00:00:00') {
                $time = $this->getHeureFormat($programme->heure);
            }
            if($dateToFrench && $time) {
                $programmes[] = (object) [
                    'date'  =>  $dateToFrench,
                    'time'  =>  $time
                ];
            }            
        }
        return $programmes;
    }


    public function getlieuxById($id)
    {
        $programmes = [];
        $transcriptions = $this->transcriptionsModel->getAll();
        foreach($this->select('id='.intval($id).' ORDER BY date') as $programme) {
            foreach($transcriptions as $transcription) {
                if($transcription->motsclefs === trim($programme->lieux, ',')) {
                    $programmes[] = $transcription->libelle;
                }
            }
        }        
        return $programmes;
    }

    public function getlieuxParentsById($id)
    {
        $programmes = [];
        $transcriptions = $this->transcriptionsModel->getAll();
        foreach($this->select('id='.intval($id).' ORDER BY date') as $programme) {
            foreach($transcriptions as $transcription) {
                if($transcription->motsclefs === $programme->lieux_parent) {
                    $programmes[] = $transcription->libelle;
                }
            }
        }
        return $programmes;
    }


    public function getTitreLibelleBySlug($slug)
    {
        foreach($this->programmes as $programme) {
            if(Util::getSlug($programme->titre) === $slug) {
                return $programme->titre;
            }
        }
        return $slug;
    }
    
    
    public function getSingleBySlug($slug)
    {
        foreach($this->programmes as $programme) {
            if(Util::getSlug($programme->titre) === $slug) {
                return $programme;
            }
        }
    }

    public function getSingleByMotsClefs($slug)
    {
        foreach($this->programmes as $programme) {
            if(Util::getSlug($programme->titre) === $slug) {
                return $programme;
            }
        }
    }

    private function getDateToFrenchIfNotPermanent($date) {

        if($date === '0000-00-00') {
            $date = 'Permanents';
        }
        else{
            $date = Util::dateToFrench($date, 'l j F Y');
        }
        return $date;
    }
    

}