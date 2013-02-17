<?php

interface BlockHandler {
	
	//public static function displayBlock($blockName, $moduleName = null);
	
	//public function display($blockName, $moduleName = null);
	
	public function resolve(Request $request, $blockName, $moduleName = null);
	
	public function resolveBlockPath($blockName, $moduleName = null);
	
	public function resolveView($blockName, $moduleName = null);
	
	public function resolveViewPath($blockName, $moduleName = null);
	
	public function viewExists($blockName, $moduleName = null);
	
	//public function showView($viewPath, Model $model, Request $request); // $blockName, $moduleName = null);
	
}

?>