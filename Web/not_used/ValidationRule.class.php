<?php
pminclude('phorge:core.ValidatorFactory');
abstract class ValidationRule {
	
	const LABEL_STRING = '__LABEL__';
	
	#protected $rule;
	protected $property;
	protected $options;
	protected $error;
	protected $result = SUCCESS;
	
	
	public function __construct($property, $options = array()){
	
		$this->property = $property;
				
		if(! is_array($options)){			
			$options = array();
		}
		
		$this->options = $options;
	}
	
	
	public function getProperty(){
		return $this->property;
	}
	
	public function setProperty($property){
		$this->rule = $property;
	}
	
	
	public function setOptions($options){
		if(is_array($options)){
			$this->options = $options;
		}else {
			$this->options = array();
		}
	}
	
	public function getOptions(){
		return $this->options;
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function setError($error){
		$this->error = $error;
	}
	
	
	public function getResult(){
		return $this->result;
	}
	
	protected function makeErrorMessage($message){
	
		if(key_exists(MESSAGE, $this->options)){			
			return $this->options[MESSAGE];			 
		}
		
				
		if(key_exists(LABEL, $this->options)){
			$label = $this->options[LABEL];
		}else {
			$label = 'Rule';
		}
		
		
		return str_replace(self::LABEL_STRING, $label, $message);
		
	
	}
	
	public function validate(Request $request){
		return true;
		/*global $log;
		try {
			
			$rule = $this->rule;
			$options = $this->options;
			
			$validator = ValidatorFactory::factory($rule); //, $request);
			try {

				
				//will throw ValidatorException if rule conditions not met									
				$validator->validate($request, $options);

			}catch (ValidationException $ve){
				$this->result = FAILURE;
				$this->error = $ve->getMessage();
				throw $ve;
			}

		}catch (ValidatorNotFoundException $vnf){
			$log->log("ValidationFactory: Validator not found for $rule", PEAR_LOG_ERR);
			throw $vnf;
		}*/
	}
	
}


?>