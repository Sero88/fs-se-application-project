<?php
class  API_Controller{
    private $curl;

    function get_api_data($url){
       
        $header =  ['Accept: application/json', 'Content-type: application/json'];                
      
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);

        
        $result = curl_exec($this->curl);

        return json_decode($result, true);        
    }

    function start(){
        $this->curl = curl_init();
    }

    function end(){
        curl_close($this->curl);
    }
}