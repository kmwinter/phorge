<?php 

class ValidatorNotFoundException extends GeneralException {
	
	private $name;
	
	public function __construct($validatorName){
		$this->name = $validatorName;
		$this->message = get_class($this) . ": " . $this->name;
	}
	
	
	public function getName(){
		return $this->name;
	}
	
	
	
	
}

?>