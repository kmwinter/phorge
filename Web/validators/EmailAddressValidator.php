<?php
pminclude('phorge:core.interfaces.ValidationRule');
class EmailAddressValidator implements ValidationRule {
	
   	public function validate(Request $request, $property, $options){

		$value =  $request->get($property);
				
		if(! empty($value)) {
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)) {
           		//throw new ValidationException('EmailAddress', ));
                return Validator::resolveMessage(Validator::LABEL_STRING . ' is not a valid Email Address', $options);
			}
		}       
	}
}
?>