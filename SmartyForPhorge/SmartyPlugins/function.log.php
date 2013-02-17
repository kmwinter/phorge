<?php

function smarty_function_log($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$message = $params['message'];
	$level = $params['level'];
	switch ($level){
		case 'warn': $level = PEAR_LOG_WARN; break;
		case 'error': $level = PEAR_LOG_ERROR; break;
		default: $level = PEAR_LOG_DEBUG;
	}
	global $log;
	
	$log->log($message, $level);
	
	
}


?>