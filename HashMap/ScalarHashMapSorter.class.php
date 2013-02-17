<?php

require_once('HashComparator.interface.php');

class ScalarHashMapSorter implements HashComparator{

    public function compare($value1, $value2){
        
		if(! (is_scalar($value1) && is_scalar($value2))){
			throw new Exception("both values must be scalar");
		}
		
		if($value1 === $value2){
			return 0;
		}
		
		if($value1 < $value2){
			return -1;
		}

		return 1;

    }	
		
	
}

?>