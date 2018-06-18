<?php

/*
* Class to parse the URL to get the request from the user
* 
* author: Sergio Esquivel
*/
class Request_Parser{
    private $request;
    private $base_uri;
    private $allowed_sort_params = array("name","mass","height");
    private $accepted_uri_args = array("app_name","page","indiv_item");

    /**
    * Breaks down URL to grab request
    * @return array $request - items 
    */
    public function get_request(){

        $request_string = strtolower($_SERVER['REQUEST_URI']);
        $request = array(); //will store request items as they are extracted from $request_string

    
        //check if user has any Query String Parameters (such as sort)
        if( !empty($_SERVER['QUERY_STRING']) || isset($_REQUEST['indiv_item'])  ){
            
            //assign to sort if it exists
            $request['sort'] =  isset($_REQUEST['sort']) && in_array( strtolower($_REQUEST['sort']), $this->allowed_sort_params ) ? strtolower($_REQUEST['sort']) : false;
            $request['order'] = isset($_REQUEST['order']) ? strtolower( filter_var($_REQUEST['order'],FILTER_SANITIZE_STRING) ) : "asc";
            $request['page_number'] = isset($_REQUEST['page']) ? filter_var($_REQUEST['page'],FILTER_SANITIZE_STRING) : null;    
            $request['indiv_item'] = isset($_REQUEST['indiv_item']) ? strtolower(urlencode(filter_var($_REQUEST['indiv_item'],FILTER_SANITIZE_STRING))) : '';

            //remove query string from user request (no longer needed)
            $request_string = str_replace("?" . strtolower($_SERVER['QUERY_STRING']),'',$request_string);
        }

        //get items from user request
        $request_items = explode( "/", $request_string ) ;            
        $accepted_args_count = count($this->accepted_uri_args);
        
        //assign items to $request based on $accepted_uri_args
        for($i = $accepted_args_count ; $i > 0 ; $i--){
            if(!empty($request[ $this->accepted_uri_args[$i - 1] ])) continue;
            $request[ $this->accepted_uri_args[$i - 1] ] = !empty($request_items[$i]) ? $request_items[$i] : null;
        }

        return $request;
    }
}