<?php

function smarty_function_structuredElement($params, &$smarty){

	$name = $params['name'];
	$type = $params['type'];
	$value = $params['value'];
	$label = $params['label'];
	$cols = $params['cols'];
	$rows = $params['rows'];
	$maxLength = $params['maxLength'];
	$size = $params['size'];
	$selected = $params['selected'];
	$options = $params['options'];
	$onClick = $params['onClick'];
	$required = $params['required'];
	
	global $structuredForm;
	global $log;
	
	if(strtolower($type) == 'hidden'){
		//changed 4/28. added $name as array key so that values can be pulled from ActionForm
		$structuredForm['hidden_inputs'][$name] = array('name'=>$name, 'value'=>$value);
		return true;	
	}
	
	if(strtolower($type) == 'button' || strtolower($type) == 'submit'){
		$structuredForm['buttons'][] = array('type'=>$type,
											'name'=>$name, 
											'label'=>$label,
											'onClick'=>$onClick);
		return true;	
	}

	
	if(strtolower($required) == 'no' || strtolower($required) == 'false'){
		$required = false;
	}
	

	
	if(!is_array($options)){
		$varSets = split(",", $options);
		$options = array();
		foreach($varSets as $set){	
			list($key, $var) = split(':', $set, 2);
			$options[$key] = $var;
		}
	}
	
	
	
	
	$error = $structuredForm['form_errors'][$name];
	if(! empty($param['error'])){
		$error = $param['error'];
	}
	

	
	$structuredForm['inputs'][$name] = array('name'=>$name,
										'type'=>$type,
										'value'=>$value,
										'label'=>$label,
										'cols'=>$cols,
										'rows'=>$rows,
										'maxLength'=>$maxLength,
										'size'=>$size,
										'options'=>$options,
										'onClick'=>$onClick,
										'required'=>$required,
										'error'=> $error);
	
	
	
}


?>