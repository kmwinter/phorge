<?php

interface ActionHandler {
	
	public function resolve($moduleName, $actionName, Request $request);
	
	public static function resolveActionPath($moduleName, $actionName);

	public static function resolveActionObjectName($moduleName, $actionName);

	
	/*====================================================================
	View Methods
	=====================================================================*/
	
	public  function resolveView($moduleName, $actionName, $result);
	
	
	public  function viewExists($viewName, $moduleName);
	
	
	public  function  resolveViewPath($viewName, $moduleName);
	
	
	public function showView($viewPath, Response $response, Request $request);
	
}


?>