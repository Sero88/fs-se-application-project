<?php

/***
* Makes call to API 
*
* author: Sergio Esquivel
***/
class  API_Controller{
    private $curl;

    /*
    * Makes actual call to get data based on the given url
    * @param string $url The url which 
    */
    public function get_api_data($url){
       
        $header =  ['Accept: application/json', 'Content-type: application/json'];                      
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $result = curl_exec($this->curl);
        
        //decode json data
        return json_decode($result, true);        
    }
    
    // starts curl connection
    function start(){
        $this->curl = curl_init();
    }

    //closes curl connection
    function end(){
        curl_close($this->curl);
    }
}