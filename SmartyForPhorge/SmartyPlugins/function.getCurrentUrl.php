<?php
pminclude('phorge:core.UrlService'); 
function smarty_function_getCurrentUrl($params, &$smarty){

	
	$anchor = $params['anchor'];	
	$subAction = $params['subAction'];
		
	
	$reservedParams = array('location', ACTION, 'properties', MODULE, ID, 'anchor');

	
	$url = UrlService::getCurrentUrl();
	
	
	if($subAction){
		$url->addParam(SUB_ACTION, $subAction);
	}
	
	foreach($params as $key=>$value){
		if(! in_array($key, $reservedParams)){
			#$properties[$key] = $value;
			$url->addParam($key, $value);
		}				
	}
	
	if($anchor){
		$url->setAnchor($anchor);
	}
			

	
	
	return $url;
	
}


?>

