<?php
class Sorter{
    private $data_field;

    private function sort_numbers_asc($n1, $n2){
		$n1 = (int)$n1[$this->data_field];
		$n2 = (int)$n2[$this->data_field];
		return $n1 - $n2;
	}
	
	private function sort_numbers_desc($n1, $n2){
		$n1 = (int)$n1[$this->data_field];
		$n2 = (int)$n2[$this->data_field];
		return $n2 - $n1;
	}
	
	private function sort_strings_asc($n1,$n2){
		return strcmp(strtolower($n1[$this->data_field]), strtolower($n2[$this->data_field]));
	}
	
	private function sort_strings_desc($n1,$n2){
		return strcmp(strtolower($n2[$this->data_field]), strtolower($n1[$this->data_field]));
    }
    
    public function sort_data(&$results, $using_field, $order, $type = "string"){
		
		//make sure sortby field exists in the array (even if it is empty) if not, return
		$key = key($results);
		if(!array_key_exists($using_field,$results[$key])) return $results;
		
		
		$this->data_field = $using_field;
			
		switch ($type){
			case "string": 
				
				if(strcmp($order, "desc") === 0){
					usort($results,array($this,"sort_strings_desc"));
				} else{
					usort($results,array($this,"sort_strings_asc"));
				}
				
			break;
				
			case "number":
				if(strcmp($order, "desc") === 0){
					usort($results,array($this,"sort_numbers_desc"));
				} else{
					usort($results,array($this,"sort_numbers_asc"));
				}				
			break;
		}
		
		return $results;
	}
}