<?php

namespace Frontend\Model;

use Core\Model;
use Core\Util;

class CategoriesModel extends Model
{
    protected $table = CATEGORIES_TABLE;

    public function getAllPureByParentOrdre()
    {
        return $this->pureQuery('SELECT * FROM '.$this->table.' ORDER BY parent_id, ordre ASC');
    }

    public function getSinglePure($id)
    {
        return $this->pureQuery('SELECT * FROM '.$this->table.' WHERE id=' . intval($id));
    }


    public function getAllByParentOrdre()
    {
        return $this->query('SELECT * FROM '.$this->table.' ORDER BY parent_id, ordre ASC');
    }

    public function getAllSlugs()
    {
        $result = $this->pureQuery('SELECT nom FROM '.$this->table);
        $slugs = [];
        while($category = mysqli_fetch_object($result)) {
            $slugs[] = Util::getSlug($category->nom);
        }        
        return $slugs;
    }

    public function getOrdresByParent($id)
    {
        $ordres = [];
        $result = $this->pureQuery('SELECT ordre FROM '. $this->table .' WHERE parent_id='.intval($id).' ORDER BY parent_id, ordre ASC');
        while($category = mysqli_fetch_object($result)) {
            $ordres[] = $category->ordre;
        }
        return $ordres;
    }

    public function getMaxOrdreByParent($parent_id)
    {
        $result = $this->pureQuery('SELECT MAX(ordre) as max_ordre FROM '. $this->table .' WHERE parent_id='.intval($parent_id));
        $category = mysqli_fetch_object($result);
        $max_ordre = $category->max_ordre;        
        return $max_ordre;
    }

    public function decreaseOrdres($current_order, $parent_id) 
    {        
        return $this->query('UPDATE '. $this->table .' SET ordre=ordre-1 WHERE ordre>'. intval($current_order) . ' AND parent_id='. intval($parent_id));
    }

    public function updateOrder($current_order, $new_order, $parent_id)
    {   
        $this->database->query('UPDATE '. $this->table .' SET ordre='. intval($new_order) .', temp=1 WHERE ordre='. intval($current_order) .' AND parent_id='. intval($parent_id));
        
        if( $current_order<$new_order ){	
            $this->database->query('UPDATE '. $this->table .' SET ordre=ordre-1 WHERE ordre>='. intval($current_order+1) .' AND ordre<='. intval($new_order) .' AND temp=0 AND parent_id='. intval($parent_id));
        }
        
        if( $current_order>$new_order){
            $this->database->query('UPDATE '. $this->table .' SET ordre=ordre+1 WHERE ordre<='. intval($current_order-1) .' AND ordre>='. intval($new_order) .' AND temp=0 AND parent_id='. intval($parent_id));
        }
        
        $this->database->query('UPDATE '. $this->table .' SET temp=0 WHERE temp=1 AND parent_id='. intval($parent_id));
    }

    public function getAllWithout($id)
    {
        return $this->query('SELECT * FROM '.$this->table.' WHERE id<>'.intval($id).' ORDER BY nom');
    }
}

