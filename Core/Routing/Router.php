<?php

namespace Core\Routing;

/**
 * Class Router
 * Assure le routage vers les différentes rubriques. 
 * Enregistre une liste d'urls types, et associe chacune d'elle à un callback - assuré par un controller. 
 */

class Router 
{

    private $url;
    private $routes = [];
    private $namedRoutes = [];


    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }


    /**
     * @param string $path
     * @param string|object $callable
     * @param string $name
     * @param string $method
     * @return Route
     */
    private function add(string $path, $callable, ?string $name, string $method): Route 
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name === null) {
            $name = $callable;
        }
        if($name) {
            $this->namedRoutes[$name] = $route;
        }       
        return $route;    
    }


    /**
     * @param string $path
     * @param string|object $callable
     * @param string $name     
     * @return Route
     */
    public function get(string $path, $callable, ?string $name=null): Route 
    {       
        return $this->add($path, $callable, $name, 'GET');
    }


    /**
     * @param string $path
     * @param string|object $callable
     * @param string $name     
     * @return Route
     */
    public function post(string $path, $callable, ?string $name=null): Route 
    {
        return $this->add($path, $callable, $name, 'POST');
    }


    public function run() 
    {
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('Request method does not exist');
        }
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if($route->match($this->url)){                                
                return $route->call();
            }
        }
        header('Location: ' . SITE_URL . 'notfound');                
    }


    public function url(string $name, array $params = []) 
    {
        if(!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

}