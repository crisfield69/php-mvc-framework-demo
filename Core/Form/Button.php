<?php

namespace Core\Form;

use Core\Form\FormWidget;

class Button extends FormWidget 
{
    public function __construct($name, $label, $type, $text,$newDisplayConfig=[])
    {   
        parent::__construct($name, 'button', $label, $value='', false, $newDisplayConfig);
                
        $this->html = ''
            .'<button '
            .'type="'.$type.'" '
            .'name="'.$this->name.'" '
            .'value="'.$this->value.'" '
            .'class="'.$this->displayConfig['widgetClass'].'" '
            .'>'
            .$text
            .'</button>';
    }    
}