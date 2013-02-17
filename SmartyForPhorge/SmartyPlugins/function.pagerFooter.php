<?php

function smarty_function_pagerFooter($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$property = $params['property'];
	$useDefault = $params['default'] == 'true' ? true:false;
	$assign = $params['assign'];
	
	

	try {
		$configVar = Framework::getConfigProperty($property, $useDefault);
		
		$smarty->assign($assign, $configVar);
		
		//return $configVar;
		
	}catch(ConfigurationException $e){
		//return false;
	}
}


?>