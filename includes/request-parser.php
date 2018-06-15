<?php
class Request_Parser{
    private $request;
    private $base_uri;
    private $allowed_sort_params = array("name","mass","height");

    function __construct(){
        //get base_uri

    }
    public function get_request(){

        //determine type of request
        

        $user_request = $_SERVER['REQUEST_URI'];

        if( isset($_REQUEST['sort']) && in_array( $_REQUEST['sort'], $this->allowed_sort_params ) ){
            //assign to $request var
            $request['sort'] = $_REQUEST['sort'];

            //remove sort param from request string
            $user_request = str_replace('?sort='.$_REQUEST['sort'], '', $user_request);
        }
        
      
        //remove base_uri from server request URI
      
        //$request = str_replace($this->base_uri,'',$_SERVER['REQUEST_URI']);
        
        //$matches = array();
       
        //$pattern = "%/([^/]*)/([^*]*)%";

        //preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches);

         //return $matches;
        $request['pages'] = explode( "/", $user_request ) ;
        
        //print_r($request);
        //index 2 contains page user wants to access
        if( !empty($request['pages'][2]) ){
            return $request;
        }
        else{
            return false;
        }
       
        
        
        //print_r($matches);
    }
}