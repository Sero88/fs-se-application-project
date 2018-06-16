<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* main web application class
**/
class Web_App{
    private $pages = array( //request => view
        "characters" => "characters",
        "character" => "characters",
        "planet-residents" => "planets", 
        "search" => "search",
        "main" => "main",
    );
    
    private $api_controller;
    
    //constructor gets necessary files
    public function __construct(){
        //get parser
        require_once 'includes/request-parser.php';
        
        //used to set args to make specific call to api
        require_once 'includes/endpoints.php';

        //api controller class
        require_once 'controllers/api-controller.php';

        //sorter class
        require_once 'includes/sorter.php';

        //view interface
        require_once 'views/view.php';
    }
    
    /*
    * main method of class
    */
    public function init(){
        //get user request
        $router = new Request_Parser();
        $request = $router->get_request();           

        //if user is not requesting a specific page or the requested page does not exist, show main page
        if( empty($request['page']) || false === $this->verify_request_exists( $request['page']) ){
            return $this->display_view();
        }

        //prepare endpoint url
        $endpoint_url = Endpoints::prepare_url($request);
       
        //get data from API           
        $data = $this->get_data($endpoint_url,$request);

        //display error if data is null
        if($data === null){
            echo '<p class="error">Error: Unable to retrieve API data</p>';
            return;
        }
      

       // return $this>-display_view($request['page'], $data);                        
    }
    
    /*
    * Displays the view based on the type of request
    *
    * @param array $data Array that contains the data retrieved from the API
    * @param string $request The request made by the user
    */
    private function display_view($request = "main", $data = null){
        //get the view file
        include_once "views/" . $this->pages[$request] . ".php";
        
        //instantiate and output data using view
        $class = ucfirst($request);
        $view = new $class($data);
        return $view->output();
    }

   /*
    * Ensures the request made by the user exists
    *
    * @param string $request String to be compared with allowed pages
    */
    private function verify_request_exists($request){
        foreach ( $this->pages as $request_name => $view ){
            if( $request === $request_name ){
                return true;
            }
        }
        return false;
    }

    /*
    * Uses $api_controller to retrieve data from the API.
    * Each page has different data needs
    *
    * @param string $url The url used to make the first call to the API
    * @param array $request Array containing the different types of requests items needed to make the API call
    */
    private function get_data($url, $request){
        $this->api_controller = new API_Controller();
        $data = null;
        
        switch ($request['page']){
            //only needs one call to grab character data
            case 'character':
                //make sure user specifies character if not, display error message
                if(empty($request['indiv_item'])){
                    echo '<p class="error">No character specified.</p>';
                    break;
                }
               
                $results = $this->api_controller->get_api_data($url);
                $data = $results['results'];
            break;

            
            case 'characters':                           
                //make first call to get initial data
                $results = $this->api_controller->get_api_data($url);
                
                //if data is empty break
                if( !isset($results['count']) && $results['count'] <= 0 ){
                    break;    
                }
                
                //assign the first set and increment counter
                $this->assign_data_results( $data, $results['results'] );                
                $next_set_url = $results['next']; 

               
                 //not sorting only gets 50 items, 10 have already been retrieved thus only 4 requests left
                if( !isset( $request['sort'] )){
                    for( $i = 1; $i <= 4; $i++ ){                       
                        //make api call                                            
                        $results = $this->api_controller->get_api_data($next_set_url);
                        
                        //assign data set
                        if( !empty($results['results']) ){
                            $this->assign_data_results( $data, $results['results'] );
                            $next_set_url =$results['next'];                                                      
                        }                      
                     }                                                                               
                } else{  //user is trying to sort then grab all data                                      
                                       
                    //assign the first set and increment counter
                    $this->assign_data_results( $data, $results['results'] );
                    $next_set_url = $results['next']; 
                    
                     //get the rest of the data left
                    //continue retrieving data until next set is null
                    do{                    
                        //make api call                                            
                        $results = $this->api_controller->get_api_data($next_set_url);
                        
                        //assign data set
                        if( !empty($results['results']) ){
                            $this->assign_data_results( $data, $results['results'] );                                                              
                        }
                        
                        //set the next set url
                        $next_set_url = !empty($results['next']) ? $results['next'] : null;
                                                
                    } while($next_set_url !== null);
                     
                    //sort the retrieved data
                    $sorter = new Sorter();

                    switch ($request['sort']){
                         case 'mass':
                         case 'height': 
                             $sorter->sort_data($data, $request['sort'], $request['order'], "number");
                         break;

                         case 'name':
                           $sorter->sort_data($data, $request['sort'], $request['order'], "string"); 
                         break;
                    }                     
                    
                }                  
            break;

            //gets planets as well as people that live on those planets
            case "planet-residents":
                //get first set of planets              
                 $results = $this->api_controller->get_api_data($url);
                 
                 //if data is empty break
                 if( !isset($results['count']) && $results['count'] <= 0 ){
                     break;    
                 }

                 print_r($results);
            break;
        }
        return $data;
    }
    
    /*
    * Loops through new set of data to assigns data retreived from the API to $data variable
    *
    * @param array &$data Array that holds the data retrieved from the API
    * @param array $results Array that contains new set of results to be added to $data 
    */
    private function assign_data_results( &$data, $results ){
        if(!empty($results) && is_array($results)){
            foreach($results as $key => $val){
                $data[] = $val;
            }
        }
    }

    private function get_rest_of_data( &$data, $results){
        
    }
}

$web_app = new Web_App();
$web_app->init();