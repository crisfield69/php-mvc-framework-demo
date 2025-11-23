<?php

namespace Core\Form;

use Core\Form\FormWidget;

class Textarea extends FormWidget
{
    public function __construct($name,$label, $value, $required=false, $newDisplayConfig=[])
    {   
        parent::__construct($name, 'textarea', $label, $value, $required, $newDisplayConfig);
        
        $required = $this->required? ' required' : '';
        
        $this->html = ''
            .'<textarea '
            .$this->displayConfig['widgetClass']
            .'name="'.$this->name.'" '
            .$required
            .'>'
            .$this->value
            .'</textarea>';
    }
}