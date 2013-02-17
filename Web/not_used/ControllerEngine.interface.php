<?php
interface ControllerEngine {
	
	public function __construct(Request $request);
	public function processModule($moduleName, $defaultAction); 
	public function getViewName();
	public function getViewPath();
	public function getActionModule();
	public function getModel();
	public function getActionName();
	public function getAction();
	
	
}


?>