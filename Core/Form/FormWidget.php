<?php

namespace Core\Form;

use Core\Form\FormConfig;

class FormWidget extends FormConfig
{
    public $name;
    public $type;
    public $required;
    public $equalId;
    public $displayConfig;

    protected $label;
    protected $value;
    protected $html;

    public $attributes;
    public $options;
    public $dataType;

    public function __construct($name, $type, $label, $value, $required = false, $newDisplayConfig = [])
    {
        parent::__construct();
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
        $this->required = $required;
        $this->options = [];
        $this->displayConfig = $this->defaultDisplayConfig;

        foreach ($newDisplayConfig as $key => $value) {
            $this->displayConfig[$key] = $value;
        }

        if (isset($this->displayConfig['attributes']))
            $this->attributes = $this->getAttributes($this->displayConfig['attributes']);

        if ($this->displayConfig['widgetClass'] !== null) {
            $this->displayConfig['widgetClass'] = 'class="' . $this->displayConfig['widgetClass'] . '" ';
        }
    }


    public function getHTML() : string
    {
        return $this->html;
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getLabel() : string
    {
        return $this->label;
    }


    private function getAttributes(array $list) : string
    {
        $attributes = '';
        foreach ($list as $key => $value) {
            $attributes .= $key . '="' . $value . '" ';
        }
        if ($attributes !== '') {
            $attributes = substr($attributes, 0, -1);
        }
        return $attributes;
    }

}
