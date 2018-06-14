<?php
class Router{
    private $request;
    private $base_uri;

    function __construct(){
        //get base_uri

    }
    public function get_request(){
      
        //remove base_uri from server request URI
      
        //$request = str_replace($this->base_uri,'',$_SERVER['REQUEST_URI']);
        
        $matches = array();
       
        $pattern = "%/([^/]*)/([^*]*)%";

        print_r( explode("/", $_SERVER['REQUEST_URI']) );
       
       
        preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches);
        
        //print_r($matches);
    }
}