<?php

namespace Core\Form;

class FormConfig
{
    public $defaultDisplayConfig;
    public $defaulContainertDisplay;
    public $defaultLabelDisplay;
    public $defaultWidgetDisplay;
    
    public function __construct()
    {
        $this->defaultDisplayConfig = [
            'containerTag'      => 'div',
            'containerClass'    => 'mb-3',
            'widgetClass'       => 'form-control',
            'labelClass'        => 'form-label'
        ];
    }
}