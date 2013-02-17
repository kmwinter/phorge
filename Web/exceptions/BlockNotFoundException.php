<?php

class BlockNotFoundException extends GeneralException  {
	
	private $blockName;
	private $moduleName;
	
	public function __construct($blockName, $moduleName = null){
		$this->moduleName = $moduleName;
		$this->blockName = $blockName;
		if($moduleName) {
			$this->message = get_class($this) . ": $blockName does not exist for module $moduleName";
		}else {
			$this->message = get_class($this) . " Global Block: $blockName does not exist";
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