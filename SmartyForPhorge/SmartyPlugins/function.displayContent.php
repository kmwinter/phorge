<?php

function smarty_function_displayContent($params, &$smarty){


	$moduleName = $params['module'];
	$blockName = $params['block'];
	$blockModule = $params['blockModule'];
	
	
			
	global $request;	
	global $log;
	//$log->log("trying to load content. $moduleName: $blockName : $blockModule", PEAR_LOG_DEBUG);
	
	if($moduleName){
		//$log->log("looking for module: $moduleName", PEAR_LOG_DEBUG)		;
		if($smarty->moduleIsLoaded($moduleName)){
			//$log->log("$moduleName loaded in Smarty Loader - displaying now", PEAR_LOG_DEBUG);
			
			$defaultAction = $smarty->getDefaultAction($moduleName);

			#echo Framework::displayModule($moduleName, $defaultAction);

		
			
		}else {
			//$log->log("Module not loaded in Smarty Loader: $moduleName", PEAR_LOG_DEBUG);
		}
	}else if($blockName){
		//$log->log("looking for block: $blockName", PEAR_LOG_DEBUG)		;
		if($smarty->blockIsLoaded($blockName)){
			
		
			echo Framework::displayBlock($blockName, $blockModule);
			
		
			
		}
		
		
	}
}


?>