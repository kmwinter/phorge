<?php

class Session { #extends HashObject {
	
	
	const ACTION_PROPERTY_KEY = '_action_properties';
	
	
	public function __construct(){
		
		//load session into hashTable
		#foreach($_SESSION as $key => $value){
		#		$this->put($key, $value);
		#}
		#print_r($_SESSION);
		
			
		
	}
	
	public function put($key, $value){
		$_SESSION[$key] = $value;	
	}
	
	public function get($key){
		if(key_exists($key, $_SESSION)){
			return $_SESSION[$key];
		}
	}
	
	public function clearProperty($key){
		unset($_SESSION[$key]);
	}
	
	public function containsKey($key){
		return key_exists($key, $_SESSION);
	}
	
	
	
	public function getActionProperty($actionName, $property){
		#return $this->hashTable[self::ACTION_PROPERTY_KEY][$actionName][$property];		
		if(!key_exists(self::ACTION_PROPERTY_KEY, $_SESSION)){
			return false;
		}
		return $_SESSION[self::ACTION_PROPERTY_KEY][$actionName][$property];		
	}
	public function putActionProperty($actionName, $property, $value){
		return $_SESSION[self::ACTION_PROPERTY_KEY][$actionName][$property] = $value;		
	}
	public function containsActionProperty($actionName, $property){
		if(! key_exists(self::ACTION_PROPERTY_KEY, $_SESSION)){
			return false;
		}
		
		if(! is_array($_SESSION[self::ACTION_PROPERTY_KEY][$actionName])){
			return false;
		}
		return key_exists($property, $_SESSION[self::ACTION_PROPERTY_KEY][$actionName]);
	}
	
	public function clearActionProperty($actionName, $property){
		unset($_SESSION[self::ACTION_PROPERTY_KEY][$actionName][$property]);		
	}
}


?>