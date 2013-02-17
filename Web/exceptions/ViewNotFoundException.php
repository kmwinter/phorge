<?php

class ViewNotFoundException extends GeneralException  {
	
	private $viewName;
	private $moduleName;
	
	public function __construct($viewName, $moduleName = null){
		$this->moduleName = $moduleName;
		$this->blockName = $viewName;
		$this->message = "View $viewName does not exist";
		if($moduleName){
			$this->message .= " in module $moduleName";

		}
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}
	
	public function getBlockName(){
		return $this->blockName;
	}
}

?>