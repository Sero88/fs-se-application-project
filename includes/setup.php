<?php
class Setup{
    private $db_name, $db_host, $db_user, $db_pass;

    function __construct($address = "config.php"){
        //config
        require_once $address;

        $this->db_name = DB_NAME;
        $this->db_host = DB_HOST;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASSWORD;

        //mysql
        //require_once 'mysql-controller.php';
    }

    //method to initiliaze web app
    public function init(){
        
        //make db connection
         $mysql_connection = new mysqli( $this->db_host, $this->db_user, $this->db_pass, $this->db_name );

         //check to make sure db was able to connect
         if($mysql_connection->connect_error !== null){
            echo "Unable to connect to Database";
            exit;
        }

        //check that db table exists, if not create it
        $db_check_query = "describe web_app_data";
        if( !$mysql_connection->query($db_check_query) ){            
            $this->create_table();
        }       
    }

    function create_table(){
       $query = "create table web_app_data(
          id 
        
        ";
    }

}