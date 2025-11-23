<?php

namespace Backend\Model;

use Core\Model;

class GalleriesPhotosModel extends Model
{
    protected $table = 'galleries_photos';
    public $datasPhotos = []; 

    public function hasDatasPhotos($gallery_id)
    {         
        $datasPhotos = $this->select('gallerie_id='. intval($gallery_id).' ORDER BY ordre ASC');
        if($datasPhotos === false || count($datasPhotos) === 0) return false;
        $this->datasPhotos = $datasPhotos;
        return true;
    }

    public function getDatasPhotos()
    {        
        return $this->datasPhotos;
    }

    public function insertDatasPhotos($photos) 
    {  
        foreach($photos as $photo) {

            $this->insert([
                'titre'         =>  [$photo->titre, 's'],
                'texte'         =>  [$photo->texte, 's'],
                'lien'          =>  [$photo->lien, 's'],
                'ordre'         =>  [$photo->ordre, 'i'],
                'gallerie_id'   =>  [$photo->gallerie_id, 'i']
            ]);

            $lastId = $this->lastId();
        }

        return $lastId;
    }

    public function orderMaxByGallery($gallery_id) 
    {  
        $maxOrdre = null;
        $result = $this->pureQuery('SELECT MAX(ordre) AS max_ordre FROM '. $this->table .' WHERE gallerie_id='. intval($gallery_id));
        $row = mysqli_fetch_row($result);
        $maxOrdre = $row['max_ordre'];
        return $maxOrdre;
    }


    public function backwardPhoto($photo, $gallery)
    {}

    public function forwardPhoto($photo, $gallery)
    {}

    public function deletedPhoto($photo, $gallery)
    {}

}