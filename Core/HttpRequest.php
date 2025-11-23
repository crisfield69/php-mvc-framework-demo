<?php

namespace Core;

class HttpRequest
{
    public $method;    
    public $post;
    public $get;    
    public $files;
    public $currentUrl;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->post = (object) $_POST;
        $this->get = (object) $_GET;
        $this->files = (object) $_FILES;
        $this->currentUrl = $this->get->url;
    }
}