<?php
pminclude('phorge:core.interfaces.Action');
pminclude('phorge:core.interfaces.Validating');
pminclude('phorge:core.Validator');
pminclude('phorge:core.ActionForm');
pminclude('phorge:core.ActionUrl');


class FormExample implements Action, Validating {
	
	
	public function doPost(Request $request, Response $response){
	
        //$errors = Validator::validate($this, $request, $response);
        $errors = $response->getErrors();
        if($errors->hasErrors()){            
            return $this->doGet($request, $response);
        }

		$response->put('message', 'Submission Successful!');		
		
		## forward on to the success view. 
		return  'success';
		
		
	}
	
	
	
	public function doGet(Request $request, Response $response){
		
		
            ## the ActionForm object encapsulates all relevant form data.
            $form = new ActionForm('formExample');

            ## set the location. This tells the form where to submit to.
            $form->setLocation(new ActionUrl('FormExample'));

            ## populate the Form object with validatior errors
            $form->setFormErrors($response->getErrors());

            #set default values for form properties. These will be overriden by any values found in the request
            $form->setValue('not_required', 'populated in action object');
            $form->setValue('approve', 'no');

            ## put the form in the response object (model)
            $response->put('form', $form);


            return 'FormExample';
	}
	
    public function validate(ValidatorErrors $errors, Request $request, Response $response){
        
        $errors->add(Validator::validateProperty('refresh', 'Refresh', array(MESSAGE=>'No duplicate posts!')));
        $errors->add(Validator::validateProperty('required', 'Exists', array(MESSAGE=>'Required Field is required (duh!)')));
        $errors->add(Validator::validateProperty('numeric', 'Numeric', array(LABEL=>'Numeric Field')));
        $errors->add(Validator::validateProperty('email', 'EmailAddress', array(MESSAGE=>'Email Address field is not a valid Address')));
        $errors->add(Validator::validateProperty('email', 'Exists', array(MESSAGE=>'Email Address is required')));
        $errors->add(Validator::validateProperty('url', 'ExternalLink', array(MESSAGE=>'Url is not a valid external Link')));
        $errors->add(Validator::validateProperty('thisorthat', 'OrExists', array('properties'=>array('one', 'two', 'three'), MESSAGE => 'Select at least one option')));
        $errors->add(Validator::validateProperty('approve', 'Equals', array('value'=>'yes', MESSAGE => 'You must accept!')));


        //return $errors;
        
    }
	
}

?>