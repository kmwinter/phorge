<?php

function smarty_function_pageTitle($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$forceActionName = $params['forceActionName'];
	$forceActionName = ($forceActionName == 'true' || $forceActionName == 'yes')? true:false;
	if($forceActionName){
		return Framework::getInstance()->getCalledAction();
	}
		
	return Framework::getInstance()->getPageTitle();

	
}


?>