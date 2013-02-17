<?php

function smarty_function_structuredButton($params, &$smarty){

	$name = $params['name'];
	$type = $params['type'];
	$label = $params['label'];
	$onClick = $params['onClick'];
	
	global $structuredForm;
	$structuredForm['buttons'][] = array('name'=>$name,
										'type'=>$type,
										'label'=>$label,
										'onClick'=>$onClick);
	

	
}


?>