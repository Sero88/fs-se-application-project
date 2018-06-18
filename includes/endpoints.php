<?php
class Endpoints{
    

    static function prepare_url($request){
        $base_url = 'https://swapi.co/api/';
        $format = '/?format=json';
        $data_type = '';
        $search_term = '';
        $page_num = !empty($request['page_number']) ? '&page=' . $request['page_number'] : '';
       
        switch ($request['page']){
            case 'characters':               
                $data_type = 'people';                
            break;

            case 'character':
                if( !empty($request['indiv_item']) ) {
                    $data_type = 'people';
                    $search_term = '&search=' . $request['indiv_item'];
                }
            break;

            case 'planet-residents':                
                $data_type = 'planets';
            break;

        }

        
        return $base_url . $data_type . $format . $search_term . $page_num;
    
    }
}