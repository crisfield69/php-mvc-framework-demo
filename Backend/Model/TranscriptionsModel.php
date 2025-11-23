<?php

namespace Backend\Model;
use Core\Model;
use Core\Util;

class TranscriptionsModel extends Model
{
    protected $table = 'transcriptions';

    public function getAllByOrdre()
    {
        return $this->query('SELECT * FROM '. $this->table .' ORDER BY ordre ASC');
    }
 

    public function getAllByTypeOrdre()
    {
        return $this->query('SELECT * FROM '. $this->table .' ORDER BY type DESC, ordre ASC');
    }


    public function getMaxOrdre()
    {
        $transcription = $this->query('SELECT MAX(ordre) AS max_ordre FROM '. $this->table, true);
        return $transcription->max_ordre;
    }


    public function updateOrder($currentOrder, $newOrder)
    {   
        $currentOrder = intval($currentOrder);
        $newOrder = intval($newOrder);

        $this->query('UPDATE '. $this->table .' SET ordre='. intval($newOrder) .', temp=1 WHERE ordre='. intval($currentOrder));

        if($currentOrder<$newOrder){	
            $this->query('UPDATE '. $this->table .' SET ordre=ordre-1 WHERE ordre>='. intval($currentOrder+1) .' AND ordre<='. intval($newOrder) .' AND temp=0');
        }
        
        if($currentOrder>$newOrder){
            $this->query('UPDATE '. $this->table .' SET ordre=ordre+1 WHERE ordre<='. intval($currentOrder-1) .' AND ordre>='. intval($newOrder) .' AND temp=0');
        }
        
        $this->query('UPDATE '. $this->table .' SET temp=0 WHERE temp=1');
    }


    public function hasTheme($theme)
    {        
        $theme = $this->select('motsclefs='.Util::escape($theme).' AND type="theme"', true);
        if(empty($theme)) return false;
        return $theme;
    }

    
    public function getLibelleByMotsclefs($motsclefs)
    {
        $transcription = $this->select('motsclefs='.Util::escape($motsclefs), true);                        
        if($transcription !== false && !empty($transcription)) {
            return $transcription->libelle;
        }
        return $motsclefs;
    }


    public function getLibelleBySlug($slug)
    {        
        $transcription = $this->select('slug='.Util::escape($slug), true);                                
        if($transcription !== false && !empty($transcription)) {
            return $transcription->libelle;
        }
        return $slug;
    }


    public function getAllLibelles()
    {
        $libelles = [];
        foreach($this->query('SELECT libelle FROM '. $this->table) as $transcription) {
            $libelles[] = $transcription->libelle;
        }
        return $libelles;
    }


    public function getAllMotsClefs()
    {
        $motsclefs = [];
        foreach($this->query('SELECT motsclefs FROM '. $this->table) as $transcription) {
            $motsclefs[] = $transcription->motsclefs;
        }
        return $motsclefs;
    }


    public function hasMotsclefs($motsclefs)
    {
        $transcription = $this->select('motsclefs='.Util::escape($motsclefs), true);
        if($transcription !== false && !empty($transcription)) {
            return $transcription;
        }
        return false;
    }




    public function hasLibelle($libelle)
    {
        $transcription = $this->select('libelle='.Util::escape($libelle), true);
        if($transcription !== false && !empty($transcription)) {
            return $transcription;
        }
        return false;
    }


    public function hasId($id)
    {
        $transcription = $this->select('id='.intval($id), true);

        if($transcription !== false && !empty($transcription)) {
            return $transcription;
        }
        return false;
    }

    public function getMotsclefsBySlug($slug)
    {
        $transcription = $this->select('slug='.util::escape($slug), true);
        if($transcription !== false && !empty($transcription)) {            
            return $transcription->motsclefs;
        }
        return false;
    }

}