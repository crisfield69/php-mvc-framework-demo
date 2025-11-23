<?php

namespace Core\Form;

use Core\Form\FormWidget;

class Input extends FormWidget 
{
       
    public function __construct($name, $type, $label, $value, $placeholder, $required=false, $newDisplayConfig=[])
    {   
        parent::__construct($name, $type, $label, $value, $required, $newDisplayConfig);       

        $required = $this->required? ' required' : '';

        $this->html = ''
            .'<input '
            .$this->displayConfig['widgetClass']
            .'type="'.$this->type.'" '
            .'type="text" '
            .'name="'.$this->name.'" '
            .'value="'.$this->value.'" '
            .'placeholder="'.$placeholder.'"'
            .$required
            .'>';
    }    
}