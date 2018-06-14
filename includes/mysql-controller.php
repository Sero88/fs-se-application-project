<?php
class Mysql_Controller{
    __construct($address = "config.php"){
        //config
        require_once $address;

        //mysql
        require_once 'mysql-controller.php';
    }

    //method to initiliaze web app
    public function init(){
        //make db connection

        $mysql_connection = new mysqli("localhost","root","root","web_app_db");
    }

}