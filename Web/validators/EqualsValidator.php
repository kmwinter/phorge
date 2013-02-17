<?php
pminclude('phorge:core.interfaces.ValidationRule');
pminclude('phorge:exceptions.ValidationException');
class EqualsValidator implements ValidationRule {
	
	
	
	public function validate(Request $request, $property, $options){
	
		
		$compare = $options['value'];	
		if(empty($compare)){					
			throw new ValidationException('Equals', "No comparison value provided for EqualsValidator. Could not validate");			
		}
		
		$value = $request->get($property); 
		
		
		if( ! ($value === $compare) ){
            return Validator::resolveMessage('Value for ' . Validator::LABEL_STRING . " Does not equal '$compare'", $options);
		}
		

	}
	
	
}




?>