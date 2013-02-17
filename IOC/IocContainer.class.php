<?php
#requires HashMap to be in php include_path
require_once('HashMap/HashMap.class.php');

/**
 * Inversioin of Control container object. Use this to interface with the
 * IOCParser which does the work of parsing components into factories
 * and also instantiating the factories' objects.
 *
 * The default Parser is a Spring-like XML configuration parser. 
 *
 * @author Kirk Winters, kirk.winters@gmail.com
 *
 */
class IocContainer {

    const PROPERTIES_FILE = '_properties';
    const PARSER_CLASS = 'ioc.parser';
    const IOC_NAMESPACE = 'ioc.namespace';
    const COMPONENT_NAMESPACE = 'ioc.component.namespace';
	
    private static $instance;
    private $parser;    
    private $properties;
    private $beanNamespace;
    private $factories;
    private $idHash;
    private $classHash;
    private $status = 'new';
    
    
	
	
	
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new IocContainer();
        }

        return self::$instance;
    }

	
	/**
	 * Constructor takes two paramaters: a config array and an optional Properties object. 
	 * The constructor will look for the PROPERTIES_FILE in the config array and if none is found
	 * will use the default properties found at ./ioc.properties.
	 * 
	 * Any additional properties included in the config array will override those found in the proprties file.
	 * 
	 * Additionally, you can provide a Properties object which will override any default properties as well. 
	 * 
	 * 
	 * 
	 * @param array $config
	 * @param Properties $addProperties
	 * 
	 */
    public function __construct($config = array(), Properties $addProperties = null){
        
        $this->properties = new Properties();
        $this->parser = null;                
        $this->idHash = array();
        $this->classHash = array();
        
        //load default properties file if exists
        if($config[self::PROPERTIES_FILE]){
           $propertiesFile = $config[self::PROPERTIES_FILE]; 
        }else {
            $propertiesFile = dirname(__FILE__) . '/ioc.properties';
        }
        
        if(!file_exists($propertiesFile)){
            throw new Exception("IOC Properties file $propertiesFile does not exist");
        }
        $this->properties->loadProperties($propertiesFile);

        //add config array properties to properties object
        foreach($config as $key=>$value){
            $this->properties->put($key, $value);
        }

        if($addProperties){
            $this->properties->combine($addProperties);
        }
        

        //determine internal ioc namespace handle
        $namespace = $this->properties->get(self::IOC_NAMESPACE);                
        #create internal namespace
        PackageManager::addNamespace($namespace, dirname(__FILE__));

    }
    
    
    /**
     * This method will return the configured IocParser implementation and create it if none has already
     * been instantiated. 
     * 
     * The IocParser object is created based on the PARSER_CLASS property found in the container's properties object.
     * This value needs to be a valid PackageManager class name (e.g. [namespace]:path.to.className. See PackageManager 
     * documentation for details). If this property is not set an exception is thrown. 
     * 
     *  Likewise, the COMPONENT_NAMESPACE is derived from the Container's properties and is passed on to the IocParser instance
     *  
     * @throws Exception
     * @return IocParser $parser 
     */
    public function getParser(){

        //delay parser creation so user can first add config params        
        if($this->parser == null){
            $parserPackage = $this->getConfigProperty(self::PARSER_CLASS);
            
            if($parserPackage == null){
                throw new Exception("No Parser found");
            }
            
            $parserName = pminclude($parserPackage);            
            $this->parser = new $parserName();            
            $this->parser->setConfigProperties($this->properties);
            
            //set external (non-ioc) namespace
            $this->beanNamespace = $this->getConfigProperty(self::COMPONENT_NAMESPACE);
            $this->parser->setNamespace($this->beanNamespace);            
        }
        
        return $this->parser;
    }
   
    
    /**
     * the init method asks the IocParser instance for all the compiled BeanFactory objects and
     * stores them in the Container's <code>factories</code> class variable. This method also 
     * indexes the factories for faster lookups.
     * 
     * It's important to note that if an attempt to call the IocContainer is made during any of the 
     * component instantiations that an IocException will be thrown in order to prevent an infinate loop
     * situation.  
     * 
     */
    private function init(){

        //do this check in case a component makes a call to IocContainer in a constructor
        //if left unchecked this would result in a infinate loop (this was fun bug to trace...)
        if($this->status == 'in_progress'){            
            throw new IocException('Call to IocContainer during component initialization');
        }
        
        if($this->status == 'initialized'){
            return;
        }
        
        $this->status = 'in_progress';
        
        try {

            //get factories from parser;
            $this->factories = $this->getParser()->getBeanFactories();

        }catch(Exception $e){
            $this->factories = array();
            $this->status = 'initialized';
            throw $e;
        }
        ##index factories for easy lookup
        foreach($this->factories as $index => $factory){
            $this->idHash[$factory->getId()] = $index;
            $this->classHash[$factory->getClass()][] = $index;
        }

        #echo 'IOC has ' . count($this->factories) . ' factories<br/>';
        $this->status = 'initialized';
    }

    
	/**
	 * Returns a single resource based on its id.
	 * 
	 * Throws an IocException if no resource is found with provided id.
	 * 
	 * @throws IocException
	 * @return mixed $resource 
	 * @param scalar $id
	 */
    public function getResourceById($id){
        $this->init();
        
        if(! key_exists($this->idHash[$id], $this->factories)){
            throw new IocException("No component with id $id found");
        }
        $factory = $this->factories[$this->idHash[$id]];

        
        return $factory->getBean();
    }
    
    
     
	/**
	 * Returns a single resource baesd on a full PackageManager classname. 
	 * 
	 * If more than a single resource is identified using the classPackage, a IocException is thrown.
	 * If no resources are found of that class, a ClassNotFoundException is thrown.
	 * 
	 * @throws ClassNotFoundException, IocException
	 * @return mixed $resource
	 * @param String $classPackage
	 */ 
    public function getResourceByClass($classPackage){
        //echo "ioc looking for $classPackage";
        $this->init();        
       
        //get all top level beans matching classPackage
        $matches = $this->getAllResourcesByClass($classPackage);
        
        //throw error if number of matches isn't exactly 1
        if(count($matches) < 1){
            throw new ClassNotFoundException("No beans found of type $classPackage");
        }

        if(count($matches) > 1){
            throw new IocException("Multiple beans found of type $classPackage");
        }

        return $matches[0];
        
    }

	/**
	 * Returns an array of resources identified by the given $classPackage parameter. 
	 * Throws an IocContainer exception if the provided classPackage is invalid. 
	 * 
	 * @throws IocException
	 * @return array $resources
	 * @param scalar $classPackage
	 */
    public function getAllResourcesByClass($classPackage){
        $this->init();
        $matches = array();

        
        
        try {
            // first include the file for the class/interface being searched by
            // and get className
            $namespace = $this->beanNamespace;
            $className = pminclude(($this->beanNamespace ? "$this->beanNamespace:" : '' ) . $classPackage);
            
        }catch(PackageManagerException $e){
            throw new IocException("Error looking up bean of class $classPackage - Class does not exist");
        }

        //look through all top level beans to see if any match the TYPE
        //(interface, extention) of class
        foreach($this->factories as $factory){

            $class = new ReflectionClass($className);
            try {
                $compareClassName = pminclude($factory->getClass());
            }catch(PackageManagerException $e){
                //some namespaces referenced in the ioc config may not be created at this point
                continue;
            }
            $compareClass = new ReflectionClass($compareClassName);

            //see if compare class is instance of search class
            if($class->isInstantiable()){
                $classObject = new $className();
                if($compareClass->isInstance($classObject)){
                    $matches[] = $factory->getBean();
                    $classObject = null;
                    continue;
                }

            }

            //see if compareClass is subclas of class
            if($compareClass->isSubclassOf($class)){
                $matches[] = $factory->getBean();
                continue;
            }



            //see if search class is interface, if so, ask if compare class
            //implements it
            if($class->isInterface()){
                if($compareClass->implementsInterface($className)){
                    $matches[] = $factory->getBean();
                    continue;
                }
            }


            
            //this could probably all be replaced with:

            #$bean = $factory->getBean();
            #if($bean instanceof $className){
            #     $matches[] = $bean;
            #}

            // but that would require instantiating an object for every factory.
            // The way it's currently written, only beans that are asked for
            // are instantiated.

        }

        return $matches;

         
    }
    

	/**
	 * Load a properties file from given filepath into the IocContainer's Properties object.
	 * 
	 *  
	 * @param String $filename
	 */
    public function loadConfigProperties($filename){
        if(file_exists($filename)){
            self::getInstance()->properties->loadProperties($filename);	
        }			
    }

    #public static function addConfigProperties(HashMap $config){
    #    self::getInstance()->addConfigPropertiesImpl($config);
    #}

	/**
	 * Combine provided HashMap with IocContainer's property object
	 * 
	 * @param HashMap $properties
	 */
    public function addConfigProperties(HashMap $properties){
        $this->properties->combine($properties);
    }

   
    /**
     * return the value from IocContainer's property object identified by the given $name
     * @return mixed $value
     * @param scalar $name
     */
    public function getConfigProperty($name){
        return $this->properties->get($name);
    }
    
	/**
	 * Set a $value for a IocContainer's property with given $name
	 * 
	 * @param scalar $name
	 * @param mixed $value
	 */
    public function setConfigProperty($name, $value){
        $this->properties->put($name, $value);
    }

	/**
	 * returns the IocContainer's property object
	 * 
	 * @return Properties $properties 
	 */
    public function getProperties() {
        return $this->properties;
    }
        
        

}

?>
