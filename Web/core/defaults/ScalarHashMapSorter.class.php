<?php

pminclude('lib:HashMap.HashComparator');

class ScalarHashMapSorter implements HashComparator{

    /**
     * @see HashComparator::compare()
     */
    public function compare($value1, $value2){
        
		if(! (is_scalar($value1) && is_scalar($value2))){
			throw new Exception("both comparable values must be scalar");
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