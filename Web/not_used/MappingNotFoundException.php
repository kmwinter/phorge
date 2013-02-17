<?php

class MappingNotFoundException extends GeneralException {
	
	public function __construct($module, $actionName){
		$this->message = "Mapping (action $actionName) not found for module $module";
	}
	
}

?>