<?php

namespace Core\Form;

use Core\Form\FormWidget;

class Select extends FormWidget
{
    public function __construct($name, $label, $options=[], $selectedValue=null, $required=false, $newDisplayConfig=[])
    {   
        parent::__construct($name, 'select', $label, $selectedValue, $required, $newDisplayConfig);

        $this->options = $options;
        $required = $this->required? ' required' : '';

        $this->html = ''
            .'<select '
            .$this->displayConfig['widgetClass']
            .$this->attributes
            .'name="'.$this->name.'" '
            .$required
            .'>';        
            
        foreach($this->options as $key=>$value) {
            
            $currentValue = $this->value;
            if(gettype($value) === 'integer' && gettype($this->value) === 'string') {
                $currentValue = intval($this->value);
            }
            if(gettype($value) === 'float' && gettype($this->value) === 'string') {
                $currentValue = floatval($this->value);
            }
            
            $selected = ($value == $currentValue)? ' selected' : '';
            $this->html .= '<option value="'.$value.'"'.$selected.'>'.$key.'</option>';
        }

        $this->html .= '</select>';
    }    
}