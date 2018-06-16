<?php
class  API_Controller{
    function get_api_data($url){
        $header =  ['Accept: application/json', 'Content-type: application/json'];
        
        $curl = curl_init();
      
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result, true);
        
    }
}