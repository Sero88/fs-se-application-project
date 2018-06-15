<?php
class Endpoints{
    

    static function prepare_url($request){
        $base_url = 'https://swapi.co/api/';
        $format = '/?format=json';
        $data_type = '';
        $search_term = '';
       
        switch ($request['pages'][2]){
            case 'characters':               
                $data_type = 'people';                
            break;

            case 'character':
                if( !empty($request['pages'][3]) ) {
                    $data_type = 'people';
                    $search_term = '&search=' . $request['pages'][3];
                }
            break;

            case 'planet-residents':
                $data_type = 'planets';
            break;

        }

        return $base_url . $data_type . $format . $search_term;
    }
}