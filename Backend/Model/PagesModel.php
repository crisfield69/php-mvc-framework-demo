<?php

namespace Backend\Model;

use Core\Model;
use Core\Util;

class PagesModel extends Model
{
    protected $table = 'pages';   

    public function getSingleBySlug($slug)
    {
        return $this->query('SELECT * FROM '. $this->table . ' WHERE slug='. Util::escape($slug), true);
    }
}