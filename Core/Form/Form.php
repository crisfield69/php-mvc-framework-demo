<?php

namespace Core\Form;

use Core\Form\FormConfig;
use Core\HttpRequest;

class Form extends FormConfig
{
    private $widgets;
    private $httpRequest;
    private $message;    
    
    public function __construct()
    { 
        parent::__construct();
        $this->httpRequest = new HttpRequest();
        $this->widgets = [];        
    }


    public function add(FormWidget $formWidget): Form
    {        
        $this->widgets[$formWidget->getName()] = $formWidget;
        return $this;
    }


    public function load() : void
    {       
        if($this->httpRequest->method === 'POST'){
            
            $posts = $this->httpRequest->post;
            $equals = [];

            foreach($posts as $key=>$value) {
                
                $widget = $this->widgets[$key];
                
                if($widget->equalId) {
                    if(!isset($equals[$widget->equalId])) $equals[$widget->equalId] = [];
                    $equals[$widget->equalId][] = $value;
                }
                
                if($widget->type === 'button') continue;
                
                if($widget->required === true) {
                    $check = $this->checkField(['required' => $value]);
                }
                
                if($widget->type === 'select') {
                    $check = $this->checkField(['select' => $value]);
                }
                
                if($widget->type === 'email') {                    
                    $check = $this->checkField(['email' => $value]);
                }
                
                if($check->result === false) {
                    $this->message = $check->message;
                }
            }

            foreach($equals as $equal){
                $check = $this->checkField(['equal' => [$equal[0], $equal[1]]]);
                if($check->result === false) {
                    $this->message = $check->message;
                }
            }
            
        }
    }
    
    
    public function getMessage() : string
    {
        return $this->message;
    }


    private function checkField( $requisits ) : object
    {
        $check = new \stdClass;
        $check->result = true;
        $check->message = '';
        
        foreach($requisits as $requisit => $fields){

            if(!is_array($fields)){
                $var = $fields;
                $fields = array($var);
            }
            
            if($requisit === 'required'){
                foreach( $fields as $field){
                    if($field === ''){
                        $check->result = false;
                        $check->message = 'Veuillez renseigner tous les champs obligatoires';
                        return $check;
                    }
                }
            }
            
            if($requisit === 'select'){
                foreach( $fields as $field){
                    if($field === 'null'){
                        $check->result = false;
                        $check->message = 'Veuillez renseigner tous les champs obligatoires';
                        return $check;
                    }
                }
            }
            
            if($requisit === 'email'){
                foreach( $fields as $field){
                    if(!preg_match( '/^[-+.\w]{1,64}@[-.\w]{1,64}\.[-.\w]{2,6}$/i', $field )){
                        $check->result = false;
                        $check->message = 'Veuillez saisir une adresse e-mail valide';
                        return $check;
                    }
                }
            }
            
            if($requisit === 'equal'){
                if($fields[ 0 ] !== $fields[ 1 ]){
                    $check->result = false;
                    $check->message = 'Veuillez saisir deux valeurs identiques';
                    return $check;
                }                
            }
            
            if($requisit === 'forbidden'){                
                if($fields[ 0 ] === $fields[ 1 ]){
                    $check->result = false;
                    $check->message = 'Veuillez saisir une valeur valide';
                    return $check;
                }                
            }
        
        }        
        return $check;
    }


    public function getHTML(): string
    {
        $html = '';
        foreach($this->widgets as $widget) {
            $html .= $this->wrap($widget);
        }
        return $html;
    }
    
    
    private function wrap(object $widget): string
    {
        $containerTag = $widget->displayConfig['containerTag'];        
        $containerClass = $widget->displayConfig['containerClass'];
        $label = $widget->getLabel();
        
        $html = ''
        . '<'.$containerTag.' class="'.$containerClass.'">'
        . '<label>'.$label.'</label>'
        . $widget->getHTML()
        . '</'.$containerTag.'>';
        
        return $html;
    }
    
    
}