<?php

class ConfigurationException extends GeneralException {
	
	public function __construct($class, $message){
		$this->message = get_class($this) . ": class $class: $message";
	}
	
	
}

?>