<?php

namespace Backend\Model;

use Core\Model;
use Core\Util;

class ConferencesModel extends Model
{
    protected $table = 'conferences';
    
    public function getConfernceByFileName($filename)
    {
        return $this->select('fichier='.util::escape($filename), true);
    }

    public function updateOrder($current_order, $new_order)
    {   
        $this->database->query('UPDATE '. $this->table .' SET ordre='. intval($new_order) .', temp=1 WHERE ordre='. intval($current_order));
        
        if( $current_order<$new_order ){	
            $this->database->query('UPDATE '. $this->table .' SET ordre=ordre-1 WHERE ordre>='. intval($current_order+1) .' AND ordre<='. intval($new_order) .' AND temp=0');
        }
        
        if( $current_order>$new_order){
            $this->database->query('UPDATE '. $this->table .' SET ordre=ordre+1 WHERE ordre<='. intval($current_order-1) .' AND ordre>='. intval($new_order) .' AND temp=0');
        }
        
        $this->database->query('UPDATE '. $this->table .' SET temp=0 WHERE temp=1');
    }

    public function getMaxOrdre()
    {
        $result = $this->pureQuery('SELECT MAX(ordre) as max_ordre FROM '. $this->table);
        $conference = mysqli_fetch_object($result);
        $max_ordre = $conference->max_ordre;
        return $max_ordre;
    }

    public function getAllOrdres()
    {
        $ordres = [];
        for($i=1; $i<=count($this->getAll()); $i++) {
            $ordres[] = $i;
        }
        return $ordres;
    }

   

}