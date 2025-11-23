<?php

namespace Backend\Model;

use Core\Model;

class BlocsModel extends Model
{
    protected $table = 'pages_blocs';   

    public function getAllByPageIdOrderByOrdre($page_id)
    {
        return $this->query('SELECT * FROM '.$this->table.' WHERE page_id='.intval($page_id).' ORDER BY ordre ASC');
    }

    public function updateOrder($currentOrder, $newOrder, $currentId)
    {   
        $currentOrder = intval($currentOrder);
        $newOrder = intval($newOrder);

        $this->query('UPDATE '. $this->table .' SET ordre='. intval($newOrder) .', temp=1 WHERE ordre='. intval($currentOrder).' AND page_id='. intval($currentId));
                                            
        if($currentOrder<$newOrder){	
            $this->query('UPDATE '. $this->table .' SET ordre=ordre-1 WHERE ordre>='. intval($currentOrder+1) .' AND ordre<='. intval($newOrder) .' AND temp=0 AND page_id='.intval($currentId));
        }
        
        if($currentOrder>$newOrder){
            $this->query('UPDATE '. $this->table .' SET ordre=ordre+1 WHERE ordre<='. intval($currentOrder-1) .' AND ordre>='. intval($newOrder) .' AND temp=0 AND page_id='.intval($currentId));
        }
        
        $this->query('UPDATE '. $this->table .' SET temp=0 WHERE temp=1');
    }

}