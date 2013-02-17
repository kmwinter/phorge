<?php
pminclude('phorge:core.interfaces.ValidationRule');
pminclude('phorge:exceptions.ValidationException');
class OrExistsValidator implements ValidationRule {
	
	public function validate(Request $request, $property, $options){
			
		if(! key_exists('properties', $options)){			
			throw new ValidationException('OrExists','Properties array not found for OrExistsValidator');
		}
		
		$values = $options['properties'];

		if(!is_array($values)){	
			throw new ValidationException('OrExists', 'Could not validate OrExists: values provided is not an array');
		}
		
		$hasParam = false;
		foreach($values as $option){			
			if($request->containsKey($option)){											
				$hasParam = true;
			}
			
		}
				
		if(!$hasParam){
			return Validator::resolveMessage('No value provided for ' . Validator::LABEL_STRING, $options);
		}
	}
	
}

?>