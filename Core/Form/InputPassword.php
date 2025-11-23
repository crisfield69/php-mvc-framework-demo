<?php

namespace Core\Form;

use Core\Form\Input;

class InputPassword extends Input 
{ 
    public function __construct($name, $label, $value, $placeholder='', $required=false, $equalId, $newDisplayConfig=[])
    {
        $this->equalId = $equalId;
        parent::__construct($name, 'password', $label, $value, $placeholder, $required, $newDisplayConfig);
    }    
}