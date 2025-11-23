<?php

namespace Core\Form;

use Core\Form\Input;

class InputText extends Input 
{    
    public function __construct($name, $label, $value, $placeholder='', $required=false, $newDisplayConfig=[])
    {        
        parent::__construct($name, 'text', $label, $value, $placeholder, $required, $newDisplayConfig);
    }    
}