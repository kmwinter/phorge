<?php

function smarty_function_formatDate($params, &$smarty){
	
	//$moduleName = $params['module'];
	
	$date = $params['date'];
        
        if(empty($date)){
            $date = 'now';
        }
	$format = $params['format'];
	
	
        return date($format, strtotime($date));  

	
}


?>