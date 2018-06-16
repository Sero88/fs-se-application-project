<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Web_App{
    private $pages = array( //request => view
        "characters" => "Characters",
        "character" => "Characters",
        "planet-residents" => "Planets", 
        "search" => "Search",
        "main" => "Main",
    );
    
    private $api_controller;
    private $swapi_response_amount = 10;

    //get necessary files for web app
    public function __construct(){
        //get parser
        require_once 'includes/request-parser.php';
        
        //used to set args to make specific call to api
        require_once 'includes/endpoints.php';

        //api controller class
        require_once 'controllers/api-controller.php';

        //sorter class
        require_once 'includes/sorter.php';
    }
    
    //initialize web app
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

        if($data === null){
            echo '<p class="error">Error: Unable to retrieve API data</p>';
        }
        print_r($data);
        
    }
    
    private function display_view($request = "main", $data = null){
        //get the view file
        include_once "views/" . $this->page[$request] . ".php";
        
        //instantiate and output data using view
        $view = new $request($data);
        return $view->output();
    }

    private function verify_request_exists($request){
        foreach ( $this->pages as $request_name => $view ){
            if( $request === $request_name ){
                return true;
            }
        }
        return false;
    }

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

                     if($request['sort'] === 'mass' || $request['sort'] === 'height'){
                        $sorter->sort_data($data, $request['sort'], $request['order'], "number");
                     } else{
                        $sorter->sort_data($data, $request['sort'], $request['order'], "string");     
                     }     
                }                  
            break;
        }
        return $data;
    }
    
    //assigns results array items to main data array
    private function assign_data_results( &$data, $results ){
        if(!empty($results) && is_array($results)){
            foreach($results as $key => $val){
                $data[] = $val;
            }
        }
    }
}

$web_app = new Web_App();
$web_app->init();


/*
require_once 'includes/setup.php';
$config = new Setup();
$config->init();
*/



//get user request










//$mysql_connection = new mysqli( "localhost", "root", "root" );

//$mysql_connection = new mysqli("localhost","root","root","web_app_db");

//print_r($mysql_connection);

/*if($mysql_connection->connect_error !== null){
    echo "Unable to connect to Database";
    exit;
}

*/
//check if table exists
//$mysql_connection-> 
