<?php

class ValidationException extends GeneralException {
	
	private $rule;
	
	public function __construct($rule, $message){
		$this->rule = $rule;
		$this->message = "$message";
	}
	
	public function getRule(){
		return $rule;
	}
	
}


?>