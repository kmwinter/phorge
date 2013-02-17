<?php

function smarty_block_structuredForm($params, $content, &$smarty, &$repeat){
		
	
	global $structuredForm;
	
	
	if (isset($content)) {
		//is end tag, display form
		
		global $template;
		

		if(! empty($structuredForm['form_object'])){

			//inject form values:
			$form = $structuredForm['form_object'];
			foreach($structuredForm['inputs'] as $inputName => $inputArray){

    			$value = $form->getValue($inputName);
    			//TODO if value empty, don't set?
    			$structuredForm['inputs'][$inputName]['value'] = $value;

			}
			
			//added 4/28, get ActionForm value for hidden_inputs
			foreach($structuredForm['hidden_inputs'] as $inputName => $inputArray){
				$value = $form->getValue($inputName);
				
				$structuredForm['hidden_inputs'][$inputName]['value'] = $value;
			}
			
    	}
		
		
		$formSmarty = new PhorgedSmarty();
		$formSmarty->setTemplate('templates', Phorge::getConfigProperty('smarty.phorge.dir'));
		foreach($structuredForm as $key=>$var){
			$formSmarty->assign($key, $var);
		}
		return $formSmarty->fetch('StructuredForm.tpl');
		
	}else {
		//is initial setup
		$structuredForm = array();

		//if ActionForm is provided, use it to set inital values...
		$actionForm = $params['formObject'];
		
		if($actionForm instanceof ActionForm){
			
			
			$structuredForm['form_object'] = $actionForm; 
			$forceErrors = $params['forceErrors'];
			$ignoreGetErrors = $forceErrors == 'true'? false: true;							
    		$formErrors = $actionForm->getFormErrors($ignoreGetErrors);
    		
    		if($actionForm->getUrl()){
    			#$action = $actionForm->getAction();
    			#$location = $actionForm->getLocation();
    			$url = $actionForm->getUrl();
    			$formAction = $url;
    			#$formAction = $location->getUrlString();
    		}else {
    			$formAction = $actionForm->getActionHref();
    		}
    		//$action = $actionForm->getActionHref();
    		$name = $actionForm->getFormName();
    		//$formValues = $actionForm->getValues();
    		
    	}

    	if(empty($formAction)){
	    	
	    	
	    	if(!empty($location)){
	    		
				$action = $params['action'];
	    			
	    		$location = $params['location'];
	    		$formAction = UrlService::getUrl($location, $action);
	    	}else {
	    		$formAction = $params['formAction'];
	    	}
    	}
    	
    	
    	
    	
    	if(! empty($params['formErrors'])){
    		$formErrors = $params['formErrors'];
    	}
    	
    	if(! empty($params['name'])){    	
    		$name = $params['name'];
    	}
    	
    	$method = $params['method'];
    	
    	$title = $params['title'];
    	

	
    	
    	$structuredForm['form_name'] = $name;
		$structuredForm['form_action'] = $formAction;
		$structuredForm['form_method'] = $method;
		$structuredForm['form_title'] = $title;
		$structuredForm['form_errors'] = $formErrors; 
		$structuredForm['inputs'] = array();
		$structuredForm['buttons'] = array();
		$structuredForm['hidden_inputs'] = array();
    						
	}
	
	
	
	/* if (isset($content)) {
	 	
	 	$out = <<<EOT
$content 
</table>
</form>
</div>
EOT;
    	return $out;
	 }else {
    	$action = $params['action'];
    	$method = $params['method'];
    	$name = $params['name'];
    	$title = $params['title'];
    	$html = "<div class=\"structured-form\">
<form action=\"$action\" ";
    	if($method){
    		$html .="method=\"$method\" ";
    	}
    	if($name){
    		$html .="name=\"$name\" ";
    	}
    	
    	$html .= '>
<table border="0" cellpadding="2" cellspacing="2">';
    	if($title){
    		$html .= "<tr>
<td class=\"structured-form-title\" cellpadding=\"2\">$title</td>
</tr>
";
    	}
    	
    	echo $html;
    		
    }
	*/
	
}

?>