<?php

class ConfigManager {
	
	/**
	 * static singleton instance
	 *
	 * @var ConfigManager
	 */
	private static $instance;
	
	/**
	 * Array of default property settings
	 *
	 * @var array of strings
	 */
	private $defaultProperties;
	
	/**
	 * Array of property settings
	 *
	 * @var array of strings
	 */		
	private $configProperties;
	
	
	
	
	/**
	 * returns singleton instance of ConfigManager
	 *
	 * @return ConfigManager 
	 */
	static  function  getInstance()  {
       if(!self::$instance)  {
           self::$instance  =  new  ConfigManager();
       }  
       
       return  self::$instance;
       
       
   } 
	
   /**
    * Shortcut static method that can be used instead of
    * ConfigManager::getInstance()->getProperty($key);
    *
    * @param String $key
    * @return String
    * @throws Exception
    */
   
   public static function get($key){
   		   		
   		return Framework::getConfigProperty($key);
   }
   
   
   /**
    * Shortcut Static method that can be used instead of
    * ConfigManager::getInstance()->setProperty($key, $value);
    *
    * @param String $key
    * @param String $value
    * @param boolean $trimInput [optional] Whether or not to use the trim function on the property value. (defaults true) 
    */
   
   public static function set($key, $value, $trimInput = true){
   		   		
   		Framework::setConfigProperty($key, $value, $trimInput);		
   }
   
   
	
   /**
    * ConfigManager constructor
    *
    */
   
	private function __construct(){
		$this->configProperties = array();	 
		$this->defaultProperties = array();
	
	}
	
	
	/**
	 * Sets default configuration value. Default configuration values
	 * can be overwritten by the standard ConfigManager->setProperty() 
	 * method. 
	 * 
	 * This method is used by the framework and should not be needed
	 * for application development
	 * 
	 * 
	 * @param String $key The key that is used to access the property
	 * @param String $value The value of the property
	 * @param boolean $trimInput [optional] Whether or not to use the trim function on the property value. (defaults true) 
	 */
	
	public function setDefaultProperty($key, $value, $trimInput = true){
		
		//$this->defaultProperties[trim($key)] = ($trimInput ? trim($value): $value);
		Framework::setConfigProperty($key, $value);
	}
	
	
	
	/**
	 * Sets configuration property. Once set, property can be retrieved using
	 * the ConfigManager->getProperty($key) method.
	 * 
	 * 
	 * @param String $key The key that is used to access the property
	 * @param String $value The value of the property
	 * @param boolean $trimInput [optional] Whether or not to use the trim function on the property value. (defaults true) 
	 */
	
	public function setProperty($key, $value, $trimInput = true){
		#$this->configProperties[$key] = ($trimInput ? trim($value): $value);
		Framework::setConfigProperty($key, $value);
	}
	
	
	/**
	 * Gets default configuration property (uaually set by framework), ignoring any 
	 * overlapping property definitions set by the standard 
	 * ConfigManager->setProperty($key, $value) ;
	 *
	 * @param String $key
	 * @return String 
	 * @throws Exception when property not set
	 */
	
	public function getDefaultProperty($key){
		
		/*
		if(key_exists($key, $this->defaultProperties)){
			return $this->defaultProperties[$key];
		}
		
		throw new Exception(get_class($this) . ": Configuration Property $key has not been set");
		*/
		Framework::getConfigProperty($key);
	}

	
	/**
	 * Gets configuration property definition for given key. 
	 *
	 * @param String $key
	 * @param boolean $allowDefault [optional] If false, will ignore default property setting (defaults to true).
	 * @return Sring 
	 * @throws Exception when propert has not been set
	 */
	
	public function getProperty($key, $allowDefault = true){
		Framework::getConfigProperty($key);
		/*
		if(key_exists($key, $this->configProperties)){
			return $this->configProperties[$key];
		}
		
		if($allowDefault){
			if(key_exists($key, $this->defaultProperties)){
				return $this->defaultProperties[$key];
			}
		}
		
		throw new Exception(get_class($this) . ": Configuration Property $property has not been set");
		*/
		
	}

	/**
	 * Returns true if property has been set, false otherwise. 
	 *
	 * @param String $key
	 * @param boolean $allowDefault [optional] if false, will ignore default setting (defaults true).
	 * @return boolean
	 */
	
	public function propertyExists($key, $allowDefault = true){
		if(key_exists($key, $this->configProperties)){
			return true;
		}
		
		if($allowDefault){
			if(key_exists($property, $this->defaultProperties)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns true if a default setting exists for property. False otherwise
	 *
	 * @param String $property
	 * @return boolean
	 */
	
	public function defaultPropertyExists($property){
		return key_exists($property, $this->defaultPropertyExists());
	}
	
	
	/**
	 * Automatically set properties loaded from a Properties object
	 *
	 * @deprecated 
	 * @param Properties $p
	 */
	
	public function loadProperties(Properties $p){
		$this->load($p, false);
	}
	
	/**
	 * Automatically set Default properties loaded from a Properties object
	 * 
	 * @param Properties $p
	 */
	
	public function loadDefaults(Properties $p){
		$this->load($p, true);

	}
	
	/**
	 * Automatically set properties loaded from a Properties object 
	 *
	 * @param Properties $p
	 * @param boolean $defaults If true, will load settings as Defaults
	 */
	
	
	
	public function load(Properties $p, $defaults = false){
		foreach($p->toArray() as $key => $value){
			
			if($defaults){			
				$this->defaultProperties[$key] = $value;
			}else {
				$this->configProperties[$key] = $value;
			}
		}
		
		
	}
	
	
	
	public function toString(){
		echo "<b>ConfigManager properties</b><br>";
		
		echo "<i>defaults</i><br>";
		foreach($this->defaultProperties as $key => $value){
			echo "$key : $value <br>";
		}
		
		echo "<br><i>custom values</i><br>";
		
		foreach($this->configProperties as $key => $value){
			echo "$key : " .  $this->getProperty($key) . "<br>";
		}
		
		echo "<br><br>";
	}
	
	
	
}



?>