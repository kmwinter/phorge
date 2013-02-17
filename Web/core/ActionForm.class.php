<?php

class ActionForm {
	
	protected $name;
	protected $properties;
	protected $formErrors;
	protected $url;
	protected $action;

	
	public function __construct($name, Url $url = null, $persist = false){

		//$this->request = $request;
		$this->name = $name;
		#$this->actionHref = $actionHref;
		if($url == null){
			$this->url = UrlService::getCurrentUrl();
		}
		
		if($persist){
			#TODO finish this	
		}
		
	}
	
	
	public function setProperty($property, $value){
		$this->setValue($property, $value);
	}
	
	public function setValue($property, $value){
		$this->properties[$property] = $value;
		
	}
	
	
	public function getValue($property){
		
		//global $request;
		$request = Framework::getRequest();
		if($request->containsKey($property)){	
			return $request->get($property);
		}
		
		if(empty($this->properties[$property])){
			return null;
		}
		return $this->properties[$property];
	}
	
	public function getValues(){
		return $this->properties;
	}
	
	/*public function clearForm(){		
		$_SESSION['forms'][$name] = array();
	}*/
	
	public function propertyHasValue($property, $value){
		//global $request;
		$request = Framework::getRequest();
		if($request->containsKey($property)){				
			$requestValue = $request->get($property);
			if(is_array($requestValue)){
				return in_array($value, $requestValue);
			}else {
				return $requestValue == $value;
			}
		}
		
		if(empty($this->properties[$property])){
			return null;
		}
		
		$propertyValue = $this->properties[$property];
		if(is_array($propertyValue)){
			return in_array($value, $propertyValue);
		}else {
			return $propertyValue == $value;
		}
		
		
	}
	
	
	public function setFormErrors($formErrors){
        
        if($formErrors instanceof ValidatorErrors){
            $this->formErrors = $formErrors->getErrors();
        }else if(is_array($formErrors)){
            $this->formErrors = $formErrors;
        }else {
            throw new Exception("Invalid formErrors object provided");
        }


		
	}
	
	
	public function getFormErrors($ignoreGetRequest = true){

		if($ignoreGetRequest){
			
			#$request = Framework::getRequest();
			
			if(strtolower($_SERVER['REQUEST_METHOD']) == 'get' ){
				return array();
			}
		}
		return $this->formErrors;		
		
	}
	
	public function getError($property, $ignoreGetRequest = true){
		if($ignoreGetRequest){
			#$request = Framework::getRequest();
			if(strtolower($_SERVER['REQUEST_METHOD']) == 'get' ){
				return false;
			}
		}
		return $this->formErrors[$property];
	}
	
	public function hasError($property, $ignoreGetRequest = true){
		if($ignoreGetRequest){
			#$request = Framework::getRequest();
			if(strtolower($_SERVER['REQUEST_METHOD']) == 'get' ){
				return false;
			}
		}
		return key_exists($property, $this->formErrors);
	}
	
	
	public function getActionHref(){
		return $this->actionHref;
	}
	
	
	public function getFormName(){
		return $this->name;
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function setUrl(Url $url){
		return $this->url;
	}
	
	public function getLocation(){
		return $this->url;
	}
	
	public function setLocation($location){
		$this->url = $location;
	}
	
	public function getAction(){
		return $this->action;
	}
	
	public function setAction($action){
		$this->action = $action;
	}
	
	
}



?>