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

    //get necessary files for web app
    function __construct(){
        //get parser
        require_once 'includes/request-parser.php';
        
        //used to set args to make specific call to api
        require_once 'includes/endpoints.php';

        //api controller class
        require_once 'controllers/api-controller.php';
    }
    
    //initialize web app
    public function init(){
        //get user request
        $router = new Request_Parser();
        $request = $router->get_request();
       
        //print_r($request);

        //if user is not requesting a specific page or the requested page does not exist, show main page
        if( false === $request || false === $this->verify_request_exists( $request['pages'][2]) ){
            return $this->display_view();
        }

        //prepare endpoint url
        $endpoint_url = Endpoints::prepare_url($request);

        //get data from API
        $api_controller = new API_Controller();
        $api_controller->get_api_data($endpoint_url);
        
    }
    
    private function display_view($request = "main", $data = NULL){
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
        switch ($request['pages'][2]){
            case 'character':
                $result = $
            break;
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
