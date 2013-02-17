<?php
pminclude('phorge:core.UrlService'); 
pminclude('phorge:core.ActionUrl');
pminclude('phorge:core.Url');
function smarty_function_getUrl($params, &$smarty){


	$module = $params['module'];
	$moduleName = $params['module'];
	$actionName = $params['action'];
	$subAction = $params['subAction'];
	$id = $params['id'];
	$anchor = $params['anchor'];
	$path = $params['path'];
	
	
	$reservedParams = array('location', ACTION,  'queryVars', 'properties', MODULE, ID, 'anchor', SUB_ACTION, 'path');
	
	$properties = $params['properties'];
	
	if(! is_array($properties)){
		$properties = array();
	}
	
	
	foreach($params as $key=>$value){
		if(! in_array($key, $reservedParams)){
			$properties[$key] = $value;
		}				
	}
	
	
	if($subAction){
		$properties[SUB_ACTION] = $subAction;
	}
	
	if($path){
		return new Url($path, $properties, $anchor);
	}else {
		return new ActionUrl($actionName, $moduleName, $id,  $properties, $anchor);	
	}


	
}


?>

