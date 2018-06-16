<?php
class Request_Parser{
    private $request;
    private $base_uri;
    private $allowed_sort_params = array("name","mass","height");
    private $accepted_uri_args = array("app_name","page","indiv_item");

    function __construct(){
        //get base_uri

    }
    public function get_request(){

        $request_string = strtolower($_SERVER['REQUEST_URI']);
        $request = array(); //will store request items as they are extracted from $request_string

    
        //check if user has any Query String Parameters (such as sort)
        if( !empty($_SERVER['QUERY_STRING']) ){
            //assign to sort if it exists
            $request['sort'] =  isset($_REQUEST['sort']) && in_array( strtolower($_REQUEST['sort']), $this->allowed_sort_params ) ? strtolower($_REQUEST['sort']) : false;
            $request['order'] = isset($_REQUEST['order']) ? strtolower( filter_var($_REQUEST['order'],FILTER_SANITIZE_STRING) ) : "asc";
            
            //remove query string from user request (no longer needed)
            $request_string = str_replace("?" . $_SERVER['QUERY_STRING'],'',$request_string);
        }
        
      
        //remove base_uri from server request URI
      
        //$request = str_replace($this->base_uri,'',$_SERVER['REQUEST_URI']);
        
        //$matches = array();
       
        //$pattern = "%/([^/]*)/([^*]*)%";

        //preg_match_all($pattern, $_SERVER['REQUEST_URI'], $matches);

         //return $matches;
        //echo $request_string;

        //get items from user request
        $request_items = explode( "/", $request_string ) ;            
        $accepted_args_count = count($this->accepted_uri_args);
        
        //assign items to $request based on $accepted_uri_args
        for($i = $accepted_args_count ; $i > 0 ; $i--){
            $request[ $this->accepted_uri_args[$i - 1] ] = !empty($request_items[$i]) ? $request_items[$i] : null;
        }

        return $request;
    }
}