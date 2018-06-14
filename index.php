<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Web_App{
    private $router;
    private $pages = array( //request => view
        "characters" => "Characters.php", 
        "planet-residents" => "Planets.php", 
        "search" => "Search.php",
        "main" => "Main.php",
    );

    //get necessary files for web app
    function __construct(){
        //get router and initialize it
        require_once 'includes/router.php';
        $this->router = new Router();
    }
    
    //initialize web app
    public function init(){
        //get user request
        $request = $router->get_request();
       
        //if user is not requesting a specific page or the requested page does not exist, show main page
        if( false === $request || false === $this->verify_request_exists($request[1]) ){
            return $this->display_view();
        }
    }
    
    private function display_view($request = "main", $data = NULL){
        //get the view file
        include_once "views/" . $this->page[$request];
        
        //instantiate and output data using view
        $view = new $view($data);
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
}


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
