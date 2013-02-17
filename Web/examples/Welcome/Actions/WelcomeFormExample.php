<?php
pminclude('phorge:core.Action');
pminclude('phorge:core.ActionForm');
pminclude('phorge:core.ModelAndView');


class WelcomeFormExample extends Action {
	
										
										
	public function __construct($actionName, $moduleName = null){			
		parent::__construct($actionName, $moduleName);
		$this->addValidationRule('refresh', 'Refresh', array('message'=>'No duplicate posts!'));
		$this->addValidationRule('required', 'Exists', array('message'=>'Required Field is required (duh!)'));
		$this->addValidationRule('numeric', 'Numeric', array('label'=>'Numeric Field'));								
		$this->addValidationRule('email', 'EmailAddress', array('message'=>'Email Address field is not a valid Address'));
		$this->addValidationRule('email', 'Exists', array('message'=>'Email Address is required'));
		$this->addValidationRule('url', 'ExternalLink', array('message'=>'Url is not a valid external Link'));
		$this->addValidationRule('thisorthat', 'OrExists', array('options'=>array('one', 'two', 'three'), 'message'=>'Select at least one option'));
		$this->addValidationRule('approve', 'Equals', array('value'=>'yes', 'message'=>'You must accept!'));		
	}
	
	protected function doWhenValid(Request $request, Response $response){
		$response->put('message', 'Submission Successful!');
		
		return new ModelAndView($response, 'success');
		
		
	}
	
	
	protected function doWhenInvalid(Request $request, Response $response){
		
		$form = new ActionForm('FormExample');
		$form->setLocation('index.php');
		$form->setAction('FormExample');
		$form->setFormErrors($this->errors);
		$form->setValue('not_required', 'populated in action object');
		$form->setValue('approve', 'no');
		
		$response->put('form', $form);
		
		return $response;
	}
	
	
	
}

?>