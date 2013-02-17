<?php

class ActionMapping {
	
	private $module;
	private $action;
	private $mappings = array();
	
	
	public function __construct($module, $action, $mappings){
		
		if(!is_array($mappings)){
			throw new ConfigurationException($this->class, "$module, $action (mappings not an array)");
		}
		
		$this->module = $module;
		$this->action = $action;
		$this->mappings = $mappings;
	}
	
	
	public function resolve($condition){
		if(! key_exists($condition, $this->mappings)){
			throw new MappingNotFoundException($condition, $module, $action);
		}
		
		return $this->mappings[$condition];
	}
	
}

?>