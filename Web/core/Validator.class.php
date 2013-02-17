<?php
pminclude('phorge:exceptions.ValidatorNotFoundException');
pminclude('phorge:core.interfaces.Validating');
class Validator {
	
    const LABEL_STRING = '__LABEL__';
    const PROPERTY = '__PROPERTY__';
    const MESSAGE = '__MESSAGE__';
    
    
    public static function validate(Validating $validating, Request $request, Response $response){

        if(! $validating instanceof Validating){
            throw new Exception("object supplied to Validator::validate does not implement interface Validating");
        }

        $errors = new ValidatorErrors();
        $validating->validate($errors, $request, $response);        
        $response->setErrors($errors);        
    }

    /**
     * This method validates a property
     *
     * @param scalar $property html form name value
     * @param scalar $ruleName Validator id. Numeric becaomes NumericValidator
     * @param array $options rule specific options
     * @return array
     *
     */
    public static function validateProperty($property, $ruleName, $options = array()){
        
        $error = array();
        $request = Phorge::getRequest();        
        $rule = Validator::factory($ruleName);
        
        $message = $rule->validate($request, $property, $options);
        
        if($message){
            
            $error[self::PROPERTY] = $property;
            $error[self::MESSAGE] = $message;
        }
        

        return $error;

    }
    
    
    
    
	public static function factory($ruleName) { 
		
		try {
			
			$className = pminclude('validators:' . ucfirst($ruleName) . 'Validator');
			return new $className($property, $options);
			
		}catch(PackageManagerException $e){
			
			Logger::error("Could not create Validator Object: $ruleName");
			throw new ValidatorNotFoundException($ruleName);
		}
		
				
	}
    
    
    public static function resolveMessage($defaultMessage, $options){
        
		if(key_exists(MESSAGE, $options)){
			return $options[MESSAGE];			 
		}
		
				
		if(key_exists(LABEL, $options)){
			$label = $options[LABEL];
		}else {
			$label = 'Rule';
		}
		
		
		$result = str_replace(self::LABEL_STRING, $label, $defaultMessage);
        return $result;
    }
	
}

?>