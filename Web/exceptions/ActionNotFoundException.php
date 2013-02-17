<?php

class ActionNotFoundException extends GeneralException  {
	
	private $actionName;
	private $moduleName;
	
	public function __construct($actionName, $moduleName = null){
		$this->moduleName = $moduleName;
		$this->actionName = $actionName;
		$this->message = get_class($this) . ": $actionName does not exist"; 
		if($moduleName){
			" for module $moduleName";
		}
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}
	
	public function getActionName(){
		return $this->actionName;
	}
}

?>