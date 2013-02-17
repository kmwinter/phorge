<?php
pminclude('phorge:core.interfaces.ValidationRule');
class NumericValidator implements ValidationRule {
	
	
	public function validate(Request $request, $property, $options){

		
		if(key_exists('min', $options)){
			$min = $options['min'];
		}
		if(key_exists('max', $options)){
			$max = $options['max'];
		}
		
		if(!$request->containsValue($property)){			
			return false;
		}else{
			$value = $request->get($property);
			if(! is_numeric($value)){
				return Valiadtor::resolveMessage(Validator::LABEL_STRING . ' is not numeric', $options);
			}elseif(! empty($min) && ($value < $min)){
				return Valiadtor::resolveMessage('Minimum value not met for ' . Validator::LABEL_STRING, $options );
			}elseif(! empty($max) && ($value > $max)){
				return Valiadtor::resolveMessage('Maximum value exceeded for ' . Validator::LABEL_STRING, $options);
			}
		}
		
	}
	
}



?>