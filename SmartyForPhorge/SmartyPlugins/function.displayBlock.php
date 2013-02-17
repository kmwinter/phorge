<?php

function smarty_function_displayBlock($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$blockName = $params['block'];
	$moduleName = $params['module'];
	global $log;
	global $request;
	return trim (Framework::displayBlock($blockName, $moduleName));
	
}


?>