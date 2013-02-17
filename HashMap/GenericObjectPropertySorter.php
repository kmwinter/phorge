<?php


require_once('HashComparator.interface.php');

class GenericObjectPropertySorter implements HashComparator {
	
	private $compareProperty;
	
	public function __construct($compareProperty){
		$this->compareProperty = $compareProperty;
		
	}
	
	public function compare($value1, $value2){
		
		$property = $this->compareProperty;
		
		if(! is_object($value1)){
			throw new Exception("value1 is not an object");
		}
		
		if(!property_exists($value1, $property)){
			throw new Exception("property $property does not exist in object " . get_class($value1));
		}
		
		$compare1 = $value1->$property;
		
		if(! is_object($value2)){
			throw new Exception("value2 is not an object");
		}
		
		if(! property_exists($value2, $property)){
			throw new Exception("property $property does not exist in object " . get_class($value2));
		}
		
		
		$compare2 = $value2->$property;
		
		if($compare1 === $compare2){
			return 0;
		}
		
		if($compare1 < $compare2){
			return -1;
		}
		
		return 1;

	}
	
	
	

	
}

?>