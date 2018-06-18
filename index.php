<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* main web application class
* author: Sergio Esquivel
**/
class Web_App{
    private $pages = array( //request => view
        "characters" => "characters",
        "character" => "character",
        "planet-residents" => "planets", 
        "search" => "search",
        "main" => "main",
    );
    
    private $api_controller;
    private $paginator = null;

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

        //class to create pagination
        require_once 'includes/paginator.php';

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
        if(null === $data){
            echo '<p class="error">Error: Unable to retrieve API data</p>';
            return;
        }
    
        //display data using a view
        return $this->display_view($request['page'], $data);                      
    }
    
    /*
    * Displays the view based on the type of request
    *
    * @param array $data Array that contains the data retrieved from the API
    * @param string $request The request made by the user
    */
    private function display_view($request = "main", $data = null){
        //get the view file
        include_once "views/template.php";
        include_once "views/" . $this->pages[$request] . ".php";
                
        //instantiate and output data using view
        $class = ucfirst($this->pages[$request]);
        $view = new $class();
        
        //get the template and echo its data
        $template = new Template();
        echo $template->get_header();
        echo $view->output($data);
        if( null !== $this->paginator && $this->paginator->get_page_count() > 1 ){
            echo $this->paginator->show_pagination();
        } 
        echo $template->get_footer();
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
       
        //start connection
        $this->api_controller->start();
        
        switch ($request['page']){
            //only needs one call to grab character data
            case 'character':
                //make sure user specifies character if not, display error message
                if(empty($request['indiv_item'])){
                    echo '<p class="error">No character specified.</p>';
                    break;
                }

                $temp_data = array(); //used to store temporary char data 
                $results = $this->api_controller->get_api_data($url);
                
                //gets the rest of the data for the character            
                if(!empty($results) && $results['count'] > 0){
                    
                    //unset data not needed
                     unset($results['results'][0]['url']);
                     unset($results['results'][0]['created']);
                     unset($results['results'][0]['edited']);

                    //start loop to get the rest of the character data
                    foreach($results['results'][0] as $key => $val){
                        if(is_array($val) && !empty($val)){
                            foreach ($val as $sub_key => $data_url){                                                              
                              $new_results = $this->api_controller->get_api_data($data_url);
                              $temp_data[$key][] = !empty($new_results['name']) ?  $new_results['name'] : $new_results['title'];
                            }
                            $results['results'][0][$key] = $temp_data[$key];
                        }  elseif(!empty($val) && strpos($val, 'https://') !== false){
                                $new_results = $this->api_controller->get_api_data($val);
                                //overwrite the urls for the actual info values
                                $results['results'][0][$key] =  !empty($new_results['name']) ?  $new_results['name'] : $new_results['title'];
                        }

                    }                                                   
                    
                                        
                } else{
                    $data['error']  = "Unable to find data for this character";
                }
                            
                //sanitize the data
                if(!empty($results['results'][0] ) ){
                    $this->sanitize_data( $results['results'][0] );
                    $data = $results['results'][0];   
                } 
                                                      
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
                                                
                    } while(null !== $next_set_url );
                     
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
                //sanitize the data
                if(!empty($data[0])) $this->sanitize_data($data[0]);                      
            break;

            //gets planets as well as people that live on those planets
            case "planet-residents":
            
                //get first set of planets              
                $results = $this->api_controller->get_api_data($url);
                
                 
                //if data is empty break
                if( !isset($results['count']) && $results['count'] <= 0 ){                
                    break;    
                }
          
                //get data needed for pagination and assign it to main data var
                $items_per_request = 10;
                $page_count = ceil($results['count'] / $items_per_request);
                $page = "/" . $request['app_name']. "/planet-residents";
                $this->paginator = new Paginator($page, $page_count); 

                //var to store list of residents from request
                $residents = array();
                
                //get all the list of unique residents from the planets in the request
                foreach($results['results'] as $key => $planet_array){
                    //if no residents exist continue
                    if( empty($planet_array['residents']) || !is_array($planet_array['residents']) ) continue; 
                    
                    //assign resident url to key (used later to retrieve data using url)
                    foreach($planet_array['residents'] as $key => $val){
                        if(!in_array($val, $residents)){
                            $residents[$val] = null;        
                       }
                    }                    
                }
        
                //request data for each resident 
                foreach($residents as $resident_url => $resident_name){                    
                   
                    if(empty($resident_url)) continue;
                   
                    $resident_data = $this->api_controller->get_api_data($resident_url);
                    if( !empty($resident_data['name']) ) {
                        $residents[$resident_url] = $resident_data['name'];
                    }
                }
                            
                //prepare the data:  planet => residents_list
                foreach( $results['results'] as $key => $planet_array ){
                    //if there are no residents add "No Residents" to planet
                    if( empty($planet_array['residents']) || !is_array($planet_array['residents']) ){
                        $data[$planet_array['name']] = array("No Residents");
                        continue;
                    }

                    //assign resident names to planet
                    foreach( $planet_array['residents'] as $res_key => $res_url ){
                       $data[$planet_array['name']][] = $residents[$res_url];
                   }
                }                   
              
                //set it as json data
                if(!empty($data)){
                    $this->sanitize_data($data);
                    $data = json_encode($data);
                }  
                               
            break;
        }

        //close connection
        $this->api_controller->end();
    
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


    /*
    * Sanitizes data received from the API
    *
    * @param array &$data Array that holds the data retrieved from the API
    * 
    */
    private function sanitize_data( &$data ){
        foreach($data as $key => $val){
            if(is_array($val)){
                foreach($val as $sub_key => $sub_val){
                    $val[$sub_key] = filter_var($sub_val,FILTER_SANITIZE_STRING);
                }
            } else{
                    $data[$key] = filter_var($val,FILTER_SANITIZE_STRING);
            }
        }
    }

}

$web_app = new Web_App();
$web_app->init();