<?php

function smarty_function_configProperty($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$property = $params['property'];
	$useDefault = $params['default'] == 'true' ? true:false;
	$assign = $params['assign'];
	
	

	try {
		$configVar = Phorge::getConfigProperty($property);
		
		if($assign){
			$smarty->assign($assign, $configVar);
		}else {
			return $configVar;
		}
	}catch(GeneralException $e){
		return false;
	}
}


?>