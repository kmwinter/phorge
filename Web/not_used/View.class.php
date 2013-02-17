<?php



class View {

	private $name;
	private $module;
	
	
	public function __construct($name, $module){
		$this->name = $name;
		$this->module = $module;
		
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getModule(){
		return $this->module;
	}
	
	public function setModule($module){
		$this->module = $module;
	}

}


?>