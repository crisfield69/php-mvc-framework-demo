<?php

namespace Frontend\Model;

use Core\Model;
use Core\Util;
use Frontend\HtmlWrapper;

class ExposantsModel extends Model
{
    protected $table = EXPOSANTS_TABLE;
    private $htmlWrapper;

    public function __construct()
    {
        parent::__construct();
        $this->htmlWrapper = new HtmlWrapper();
    }

    public function getAllByCategory($category) 
    {
        $condition = '';
        $exposants = null;
        $result = $this->pureQuery('SELECT * FROM exposants_categories WHERE categorie_id='.intval($category));
        if($result === false || mysqli_num_rows($result) === 0) return null;
        while($match = mysqli_fetch_object($result)) {
            $condition .= 'id=' . intval($match->exposant_id) . ' OR ';
        }       
        $condition = trim($condition, ' OR ');
        $condition .= ' ORDER BY nom ASC';
        $exposants = $this->select($condition);
        return $exposants;
    }

    public function getAllByCategorySlug($slug)
    {
        $result = $this->pureQuery('SELECT nom FROM categories WHERE slug='.util::escape($slug));
        if($result === false || mysqli_num_rows($result) === 0) return null;
        $category = mysqli_fetch_object($result);
        $nom = $category->nom;
        $exposants = [];
        $result = $this->pureQuery('SELECT * FROM '.$this->table.' WHERE activity_sector='.util::escape($nom).' OR specialties='.Util::escape($nom));
        while($exposant = mysqli_fetch_object($result)) {
            $exposant->slug = util::getSlug($exposant->name);
            $exposants[] = $exposant;
        }
        return $exposants;
    }

    
    public function getSingleBySlug($slug)
    {           
        $result = $this->pureQuery('SELECT * FROM '.$this->table);
        while($exposant = mysqli_fetch_object($result)) {
            if(Util::getSlug($exposant->name) === $slug) {

                // Labels
                $labels = [];
                $explode = explode('|||', $exposant->labels);
                if(count($explode)>0 && !empty($explode[0])) {
                    foreach($explode as $label) {
                        $label = Util::getSlug($label);
                        $labelEndPath = 'images/exposants/certification/' . $label . '.jpg';
                        if(file_exists(PUBLIC_PATH . $labelEndPath))
                            $labels[] = (object) [
                                'image' =>  PUBLIC_URL . $labelEndPath,
                                'title' =>  CERTIFICATIONS[$label][0],
                                'url'   =>  CERTIFICATIONS[$label][1]
                            ];
                    }
                }
                $exposant->labels = $labels;

                // Socials
                $exposant->facebook = $this->htmlWrapper->getSocialsWrapper($exposant->facebook, 'facebook');
                $exposant->instagram = $this->htmlWrapper->getSocialsWrapper($exposant->instagram, 'instagram');

                // Contact 
                $exposant->communication_email = $this->htmlWrapper->getLinkWrapper($exposant->communication_email, 'Mel', true);
                $exposant->website = $this->htmlWrapper->getLinkWrapper($exposant->website, 'Web');

                return $exposant;
            }
        }
       return null;
    }


    public function getAllSlugs()
    {
        $result = $this->pureQuery('SELECT name FROM '.$this->table);
        $slugs = [];
        while($exposant = mysqli_fetch_object($result)) {
            $slugs[] = Util::getSlug($exposant->name);
        }        
        return $slugs;
    }

}