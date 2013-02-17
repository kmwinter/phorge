<?php
interface FrameworkInterface  {
	
	public static function displayModule(Request $request, $moduleName, $defaultAction = DEFAULT_VALUE);
	public static function displayBlock(Request $request, $blockName, $moduleName = null);
	public function __construct(Request $request);	
	public function processModule($moduleName, $defaultAction = DEFAULT_VALUE);
	public function processBlock($blockName, $moduleName = null);
		
	
	/*
	public function getDefaultAction();
	public function setDefaultAction($defaultAction);	
	public function getModule();
	public function setModule($module);
	public function go(); //$moduleName, $defaultAction = null);
	//public static function displayBlock($blockName, $moduleName = null);
	*/
}

?>