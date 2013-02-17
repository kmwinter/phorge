<?php


require_once('HashComparator.interface.php');
class GenericObjectMethodSorter implements HashComparator {
	
	private $compareMethod;
	
	public function __construct($compareMethod){
		$this->compareMethod = $compareMethod;
	}
	
	public function compare($value1, $value2){
		
		$method = $this->compareMethod;
		
		if(! is_object($value1)){
			throw new Exception("value1 is not an object");
		}
		
		if(!method_exists($value1, $method)){
			throw new Exception("method $method does not exist in object " . get_class($value1));
		}
		
		$compare1 = $value1->$method();
		
		if(! is_object($value2)){
			throw new Exception("value2 is not an object");
		}
		
		if(!method_exists($value2, $method)){
			throw new Exception("method $method does not exist in object " . get_class($value2));
		}
		
		
		$compare2 = $value2->$method();
		
		
		
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