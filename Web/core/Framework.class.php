<?php


pminclude('phorge:core.Session');
pminclude('phorge:core.Request');
pminclude('phorge:core.Response');
pminclude('phorge:core.DispatcherFactory');
pminclude('phorge:core.Out');
pminclude('phorge:core.UrlService');
pminclude('phorge:core.Logger');
pminclude('phorge:core.AuthManager');

/**
 * This Framework class does most of the heavy lifting. It acts as the central nevous system
 * for everything. It holds the request response, session, and config-properties objects and provides an easy 
 * interface for fetching them. 
 * 
 * This class also provides the interface for displaying actions and blocks
 * 
 */


class Framework  { 
    
    
    /**
     * Static singleton instance
     *
     * @var Framework
     */
    private static $instance;	
    
    /**
     * The Request object 
     *
     * @var Request
     */	
    private $request;
    
    /**
     * The response for the main page action
     * 
     * @var Response
     */
    private $response;
        
    
    #private $blockResponse;
    
    
    /**
     * Properties container
     *
     * @var Properties
     */
    private $properties;
    
    /**
     * ModuleConfigurer
     *
     * @var ModuleConfigurer
     */
    private $moduleConfig;
    
    /**
     * Session object
     *
     * @var Session
     */
    private $session;

    /**
     * IocContainer instance
     *
     * @var IocContainer
     */
    private $ioc;

    /**
     *  isInitialized flag
     *
     * @var boolean
     */
    private $initialized = false;
    
    ##==============================================================================
    ## static methods ##
    
    /**
     * Singleton accessor for Framework instance
     *
     * @return Framework
     */
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new Framework();									
        }
        
        return self::$instance;
    }
    
    
   
    /**
     * Initialize the framework. Automatically called upon executeAction
     */
    public static function initialize(){
        $instance = self::getInstance();
        if(!$instance->initalized){
            $instance->init();
        }                
    }
    




    /**
     * execute the action defined by the $actionName and optional $moduleName
     * parameters
     *
     * @param String $actionName
     * @param String $moduleName (optional)
     */
    public static function executeAction($actionName, $moduleName = null){
        self::initialize();
        $response = new Response($actionName, $moduleName);		
        return self::getInstance()->processAction($actionName, $moduleName, $response);
    }

    /**
     * use executeAction instead
     *
     * @deprecated
     * @param String $actionName
     * @param String $moduleName (optional)
     */
    public static function displayAction($actionName, $moduleName = null){					
        self::initialize();
        Logger::warn("displayAction is deprecated. Please use Phorge::executeAction instead");
        return Framework::executeAction($actionName, $moduleName);
    }


    /**
     * Execute the action found by parsing the URL
     *
     * @return mixed action result
     */
    public static function executeUrlAction(){
        self::initialize();
		
        $actionName = UrlService::getAction();
        $moduleName = UrlService::getModule();
        return self::executeAction($actionName, $moduleName);
    }

    /**
     * Use executeUrlAction instead
     *
     * @deprecated
     * @return mixed action result
     */
    public static function displayUrlAction(){
        self::initialize();
        Logger::warn("displayUrlAction is deprecated. Please use Phorge::executeUrlAction instead");
        return Phorge::executeUrlAction();
    }



    /**
     * Forward the given Response to the provided action/module. Returns
     * the view returned by the forwarded action.
     *
     *
     * @param Request $request
     * @param Response $response
     * @param String $actionName
     * @param String $moduleName (optional)
     * @return String viewname
     */
    public static function forward(Request $request, Response $response, $actionName, $moduleName = null){
        //execute action with provided response, return view name
        Logger::debug("Forwarding to action $actionName" . ($moduleName? " in modue $mduleName":""));
        $module = self::getModule($moduleName);        
        $modelAndView = $module->getActionOutput($request, $response, $actionName);
        
        return $modelAndView->getView();
        
    }
    /**
     * execute a Block object given the provided blockName/moduleName
     *
     * @param Request $request
     * @param string $blockName
     * @param string $moduleName = null
     * @return mixed result from block execution
     */
    public static function executeBlock($blockName, $moduleName = null, $response = null){
        self::initialize();
        return self::getInstance()->processBlock($blockName, $moduleName, $response);
    }
    
    /**
     * @deprecated
     * @param Request $request
     * @param string $blocName
     * @param string $moduleName = null
     * @return mixed result from BlockController
     */
    public static function displayBlock($blockName, $moduleName = null, $response = null){
        return Framework::executeBlock($blockName, $moduleName, $response);
    }

        
    
    /**
     * set global ModuleConfiguration object
     *
     * @param ModuleConfiguration $config
     */
    private static function setModuleConfig(ModuleConfigurer $config){
        
        self::getInstance()->moduleConfig = $config;
    }

    /**
     * Return the global ModuleConfigurer object
     * 
     * @return ModuleConfigurer
     */
    public static function getModuleConfig(){
        
        return self::getInstance()->moduleConfig;
    }

    /**
     * returns the global Request object
     *
     * @return Request
     */
    public static function getRequest(){
        
        return self::getInstance()->request;
    }	

    public static function setRequest(Request $request){
        
        self::getInstance()->request = $request;

    }

    
    /**
     * static shortcut for instance->getResponse(). reterns the main action's response
     *
     * @return Response
     */	
    public static function getResponse(){
        #self::initialize();
        return self::getInstance()->response;
    }
    
    public static function setResponse(Response $response){
        #self::initialize();
        self::getInstance()->response = $response;
        
    }
    
    
    
    /**
     * Load configuration properties from file
     *
     * @param String $path
     */
    public static function loadConfigProperties($path){
        #self::initialize();
        if(file_exists($path)){
            self::getInstance()->properties->loadProperties($path);	
        }
        
    }
    
    
    /**
     * Retrieve configuration property
     *
     * @param String $key
     * @return String
     */
    public static function getConfigProperty($key){
        return self::getInstance()->properties->get($key);
    }


    /**
     * get global Properties object
     *
     * @return Properties
     */
    public static function getConfigProperties(){
        return self::getInstance()->properties;
    }


    /**
     * Set a configuration property
     *
     * @param String $key
     * @param String $value
     */
    public static function setConfigProperty($key, $value){
        self::getInstance()->properties->put($key, $value);
    }
    

    public static function addConfigProperties(HashMap $config){
        self::getInstance()->properties->combine($config);
    }



    public static function setIocContainer(IocContainer $ioc){
        self::getInstance()->ioc = $ioc;
    }

    /**
     * get IocContainer
     *
     * @return IocContainer
     */
    public static function getIocContainer(){
        return self::getInstance()->ioc;
    }


    /**
     * Gets the page title
     *
     * @return String
     */
    public static function getPageTitle(){
        return self::getInstance()->pageTitle;
    }
    
    /**
     * set the page title
     *
     * @param String $pageTitle
     */
    public static function setPageTitle($pageTitle){
        self::getInstance()->pageTitle = $pageTitle;
    }
    
    
    
    
    /**
     * return Session object
     *
     * @return Session
     */
    public static function getSession(){
        return self::getInstance()->session;
    }

    
    
    ##========================= Module Functions ======================

    /**
     * create and return a Module object by specifying the filesystem path
     * to the module root
     *
     * throws an exception if an invalid path is provided
     *
     * @param String $modulePath
     * @return Module
     *
     */
    public static function createModuleFromPath($modulePath){
        if(is_dir($modulePath)){           
            $module = new Module();
            $module->setDirectory($modulePath);
            $module->setModuleName(dirname($modulePath));
            return $module;
        }else {
            throw new Exception("$modulePath is not a valid modulePath");
        }
    }

    /**
     * Add a Module object to the application
     *
     * @param Module $module
     */
    public static function registerModule(Module $module){
        self::getInstance()->moduleConfig->addModule($module);
    }

    /**
     * adds an array of Module objects to the application
     *
     * @param array<Module> $moduleArray
     *
     */
    private static function registerModules($moduleArray){
        foreach($moduleArray as $key => $module){
            if($module instanceof Module){
                self::getInstance()->moduleConfig->addModule($module);
            }else {
                Logger::warn("moduleArray item $key is not instance of Module");
            }
        }
    }

    /**
     * Get module identified by a moduleName. If moduleName is null the
     * default module will be returned
     *
     * @param String $moduleName
     * @return Module
     *
     */
    public static function getModule($moduleName = null){
        $moduleConfig = self::getInstance()->moduleConfig;
        return $moduleConfig->getmodule($moduleName);
    }

    
    /**
     * Configures your Phorge application. 
     * 
     * First parameter is the full path to your application root directory.
     * 
     * Second parameter is either an array of ioc xml file names or a comma
     * separated list of ioc xml files
     * 
     *
     * @param String $approot
     * @param mixed $xmlFileName
     */
    public static function configureApplication($approot = null, $xmlFileName = null){

        if($approot){
            Phorge::setConfigProperty(APPLICATION_ROOT, $approot);
        }
		
        if($xmlFileName){
            if(is_array($xmlFileName)){
                $files = $xmlFileName;
            }else {
                //parse comma separated list into array
                $files = split(',', $xmlFileName);

                foreach($files as $i => $file){
                    $file = trim($file);
                    $files[$i] = rtrim(Phorge::getConfigProperty(APPLICATION_ROOT), '/') . "/$file";
                }

            }
            Phorge::setConfigProperty(APPLICATION_XML_CONFIG, $files);
        }
        
        $moduleDir = "$approot/" . Phorge::getConfigProperty(MODULE_DIRNAME);
        Phorge::setConfigProperty(MODULE_DIR, $moduleDir);
        
        PackageManager::addDefaultNamespacePath(Phorge::getConfigProperty(APPLICATION_ROOT));
        
    }
    
    
    
    ##==============================================================================##
    
    /**
     * private constructor for Framework.
     * 
     */
    private function __construct(){		
        $this->response = null;
        #$this->blockResponse = new BlockResponse();
        $this->properties = new Properties();
        $this->session = new Session();
        $this->subActions = array();		
    }
    

    /**
     * initialize the Framework
     *
     * @global array $processingTime
     *
     *
     */
    private function init(){

        if($this->initialized){
            return;
        }

        global $processingTime;
        $processingTime['init']['start'] = microtime(true);

        ##==============================================================

        //create moduleConfigurer
        pminclude('phorge:core.ModuleConfigurer');
        $moduleConfig = new ModuleConfigurer();
            
        
        $moduleConfig->setModuleRoot(Phorge::getConfigProperty(MODULE_DIR));
        $moduleConfig->configure();
        $this->moduleConfig = $moduleConfig;

        
        #instantiate main IOC container
        try {
            $ioc = new IocContainer(array(), Phorge::getConfigProperties());
            
            $this->ioc = $ioc;
        }catch(Exception $e){
            die("Error thrown while instantiating IocContainer: " . $e->getMessage());
        }

        
        #create dummy log, can/should be overridden by application config
        Logger::initialize();


        /*
        try {
            $moduleConfig = $ioc->getResourceByClass('phorge:core.ModuleConfigurer');
        }catch(IocException $e){
            pminclude('phorge:core.ModuleConfigurer');
            $moduleConfig = new ModuleConfigurer();
        }
        */



        ## look for modules defined in IOC xml config
        try {
            
            $modules = $ioc->getAllResourcesByClass('phorge:core.Module');
            
            Phorge::registerModules($modules);
        }catch(Exception $e){
            die("Error thrown while collecting and registering modules from IOC config: " . $e->getMessage());

        }


        if(! $moduleConfig->hasDefaultModule()){
            //create default module
            pminclude('phorge:core.Module');
            $module = new Module();
            $module->setName($moduleConfig->getDefaultModuleName());
            $module->setDirectory(Phorge::getConfigProperty(APPLICATION_ROOT));
            $module->setNamespace($moduleConfig->getDefaultModuleName());
	
            $moduleConfig->addModule($module);
			

        }

        
        
        //get action filters from Ioc
        $filters = $ioc->getAllResourcesByClass('phorge:core.interfaces.ActionFilter');

        ## add built in filters
        pminclude('phorge:core.defaults.DefaultLoginFilter');
        pminclude('phorge:core.defaults.AuthFilter');
        pminclude('phorge:core.defaults.ValidationFilter');
        ## watches for login
        $filters[] = new DefaultLoginFilter();
        #watches for authorizing actions
        $filters[] = new AuthFilter();
        #for default validation
        $filters[] = new ValidationFilter();


        $moduleConfig->addGlobalFilters($filters);

        ## beacuse underlying user object is probably stored in the session,
        ## that class must be defined before the session is initialized.
        ## initializing the AuthHandler instance should take care of this
        AuthManager::initialize();

        ## start PHP session
        session_start();

        ## instantiate Request object
        $this->request = new Request;

        ## crete validators namespaces
        #TODO get this out of ioc xml somehow
        PackageManager::addNamespace('validators', Framework::getConfigProperty(VALIDATION_DIR));
        PackageManager::addNamespace('validators', Framework::getConfigProperty(DEFAULT_VALIDATION_DIR));


        #parse Module and Action from URL
        UrlService::parse();


        ##end initialization
        $this->initialized = true;
        $processingTime['init']['stop'] = microtime(true);

    }

    
    /**
     * Processes Action given an $actionName and optional $moduleName.
     * Instantiates ActionController object and outputs view
     *
     * @param String $actionName
     * @param String $moduleName
     * @return mixed result from $module->getActionOutput method
     */
    private function processAction($actionName, $moduleName, Response $response ){

        $start = microtime(true);        
        $timeIndex = "[action]" . $_SERVER['REQUEST_URI'];
        
        global $processingTime;
        $processingTime[$timeIndex]['start'] = $start;

        #$result = false;

        Logger::debug("Processing $timeIndex");


        ## this will/should throw and exception if module is missing
        $module = $this->moduleConfig->getModule($moduleName);
        
		
        ## thrown in for debugging purposes. If the LOG_DIAGNOSTIC property is set to true
        ## the module will output its configuration at this point (prior to action execution)
        ## can be helpful if you absolutely can't figure out why something isn't working
        if(Phorge::getConfigProperty(LOG_DIAGNOSTIC)){
            Logger::debug($module->diagnostic());
        }
		
		## get Request object from Framework
        $request = Framework::getRequest();
        
		## if actionName is null, use module's default action
        if($actionName == null){
            $actionName = $module->getDefaultAction();
        }

		## begin action processing...
        $modelAndView = $module->getActionOutput($request, $response, $actionName);
        $output = $module->renderView($modelAndView, $request);        
        $writer = $module->getWriter();
        $output = $writer->writeAction($output);

        $processingTime[$timeIndex]['stop'] = microtime(true);
        # return rendered action output 
        return $output;
                        
            
       
    }
        
    
    /**
     * Process Block 
     * Exceptions are displayed inline instead of thrown. 
     *
     * @param String $blockName
     * @param String $moduleName
     * @return mixed result from $module->getBlockOutput method
     */
    
    private function processBlock($blockName, $moduleName, $response = null){
        $timeKey = "[block] $blockName";
        global $processingTime;
        $processingTime[$timeKey]['start'] = microtime(true);
                
        $moduleConfig = Framework::getModuleConfig();        
        $module = $moduleConfig->getModule($moduleName);
        if(! $response){
            $response = new Response($blockName, $moduleName);
        }
        $request = Framework::getRequest();
        $modelAndView = $module->getBlockOutput($request, $response, $blockName);
        
        $output = $module->renderView($modelAndView, $request);        
        $writer = $module->getWriter();
        $output = $writer->writeBlock($output);        
        $processingTime[$timeKey]['stop'] = microtime(true);
        return $output;

        
    }
    
    /**
     * takes a filepath and returns any output generated by that PHP file as a
     * String
     *
     *
     * @param String $filePath
     * @return String
     */
    public static function getIncludeFileContent($filePath){
        
        ob_start();
        require $filePath;
        $result = ob_get_contents();
        ob_end_clean();			        
        return $result;		
    }
        
    /**
     * For logging purposes. Takes an object array, exceutes a print_r call using
     * $value as its parameter and returns the result as a string.
     *
     *
     * @param mixed $value
     * @return String
     */
    public function print_rToString($value){
        ob_start();
        print_r($value);
        $result = ob_get_contents();
        ob_end_clean();
        #Logger::debug($result);
        return $result;

    }


    

    
}
?>