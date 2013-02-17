<?php

interface ViewResolver { 

	//public function resolve(Module $module, Action $action);
	public  function resolve($moduleName, $actionName, $result);
	public  function  resolveViewPath($viewName, $moduleName);
	//public static function viewExists($moduleName, $viewName);

	public  function viewExists($viewName, $moduleName);
	
	
	public function resolveBlock($blockName, $moduleName = null);
	public function resolveBlockViewPath($viewName, $moduleName = null);
	public function blockViewExists($viewName, $moduleName = null);	
	
}


?>