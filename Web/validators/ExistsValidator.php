<?php
pminclude('phorge:core.Validator');
pminclude('phorge:exceptions.ValidationException');
pminclude('phorge:core.interfaces.ValidationRule');
class ExistsValidator implements ValidationRule{
	
	
	
	public function validate(Request $request, $property, $options){
		
		
		if(! $request->get($property)){			
            $message = Validator::resolveMessage('Value must be specified for ' . Validator::LABEL_STRING, $options);            
            return $message;            			
		}
		
		

	}
	
	
}


?>