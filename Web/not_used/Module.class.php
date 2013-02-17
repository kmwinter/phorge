<?php

class Module {
	
	protected $moduleName;
	protected $actions;
	protected $views;
	protected $customController;
	
	
	
	public function __construct($moduleName){
		$this->moduleName = $moduleName;
		
		if(!file_exists(MODULE_DIR . "/$moduleName")){
			throw new ConfigurationException(get_class($this), "No $moduleName module directory found");
		}
		
		if(!file_exists(MODULE_DIR . "/$moduleName/Actions")){
			throw new ConfigurationException(get_class($this), "No Actions Directory for $moduleName module");
		}
		
		
		if(!file_exists(MODULE_DIR . "/$moduleName/Views")){
			throw new ConfigurationException(get_class($this), "No Views Directory for $moduleName module");
		}
		
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}
	
	
	public function viewExists($viewName){		
		
		//return in_array($viewName, $this->views);
		$resolver = $this->getViewResolver();
		return file_exists($resolver->resolveViewPath($this->moduleName, $viewName));
		//return file_exists(resolveViewPath($this->moduleName, $viewName));
	}
	
	public function actionExists($actionName){
		
		//return in_array($actionName . 'Action', $this->actions);		
		$resolver = $this->getActionResolver();
		return file_exists($resolver->resolveActionPath($this->moduleName, $actionName));
		//return file_exists(resolveActionPath($this->moduleName, $actionName));
	}
	
	
	public function getViewResolver(){
		$objectName = $this->moduleName . 'ViewResolver';
		$path = MODULE_DIR . "/$objectName.php";
		
		
		if(file_exists($path)){
			require $path;
			
		}else {
			$objectName = VIEW_RESOLVER;
		}
		
		$object = new $objectName;
		
		return $object;
	}
	
	
	public function getActionResolver(){
		$objectName = $this->moduleName . 'ActionResolver';
		$path = MODULE_DIR . "/$objectName.php";
		
		
		if(file_exists($path)){
			require $path;
			
		}else {
			$objectName = ACTION_RESOLVER;
		}
		
		$object = new $objectName;
		
		return $object;
	}
	
	
	
}