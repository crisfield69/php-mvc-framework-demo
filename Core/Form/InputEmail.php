<?php

namespace Core\Form;

use Core\Form\Input;

class InputEmail extends Input 
{    
    public function __construct($name, $label, $value, $placeholder='', $required=false, $newDisplayConfig=[])
    {
        parent::__construct($name, 'email', $label, $value, $placeholder, $required, $newDisplayConfig);
    }    
}