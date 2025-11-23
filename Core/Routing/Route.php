<?php

namespace Core\Routing;

class Route 
{

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }


    public function match($url) 
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches)) {
            return false;
        }        
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }


    private function paramMatch($match)
    {
        if(isset($this->params[$match[1]])) {
            return '('.$this->params[$match[1]].')';
        }
        return '([^/]+)';
    }


    public function call() 
    {   
        if(is_string($this->callable)){
            $params = explode('@', $this->callable);
            $controller = "Frontend\\Controller\\" . $params[0]."Controller";
            $controller = new $controller();
            $method = $params[1];            
            return call_user_func_array([$controller, $method], $this->matches);
        }
        else{
            if(is_array($this->callable)){
                $array = $this->callable;
                $class = $array[0];
                $method = $array[1];
                $arguments = []; 
                if(isset($array[2])) $arguments = $array[2];
                else $arguments = $this->matches;
                return call_user_func_array([new $class(), $method], $arguments);
            }
            else {
                return call_user_func_array($this->callable, $this->matches);
            }
        }
    }


    public function with($param, $regex) 
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }
    

    public function getUrl($params) 
    {
        $path = $this->path;
        foreach($params as $k=>$v) {
            $path = str_replace(":$k", $v, $path);

        }
        return $path;
    }

}