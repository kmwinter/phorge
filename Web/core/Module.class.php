<?php


/**
 * 
 *
 * @author kwinters
 */

//require "defaults/PhorgeWriter.class.php";
pminclude('phorge:core.defaults.PhorgeWriter');
pminclude('phorge:core.interfaces.ActionDispatcher');
pminclude('phorge:core.interfaces.BlockDispatcher');
pminclude('phorge:core.interfaces.ViewDispatcher');
class Module {

    private $properties;
    private $isConfigured = false;
    private $name;
    private $namespace;
    private $configFile;
    private $directory; 
    private $defaultAction;
    private $actionDispatcher;
    private $blockDispatcher; #?
    private $viewDispatcher;
    private $exceptionDispatcher;
    private $writer;
    private $filters = array();
    private $iocContainer;

    public function __construct(){
        $this->properties = new Properties();
    }


    /**
     * Take the actionName and return the rendered view content.
     *
     * This method also runs the pre/post filters associated with this module
     *
     * Exceptions thrown by the Action execution and subsequent view rendering
     * are caught and handled by this module's ExceptionDispatcher instance.
     * Exceptions are similarly rendered into presentation content and returned.
     *
     *
     * @param Request $request
     * @param Response $response
     * @param String $actionName
     * @return mixed view result
     */
    public function getActionOutput(Request $request, Response $response, $actionName){
      
        try {
            
            $filters = $this->filters;
            
            $action = $this->actionDispatcher->getAction($actionName, $request, $response);
            
            foreach($filters as $name => $filter){
                $filter->executePreFilter($action, $request, $response);
            }            
            
            
            $modelAndView = $this->actionDispatcher->getModelAndView($action, $request, $response);
            
            foreach($filters as $name => $filter){
                $filter->executePostFilter($action, $request, $modelAndView);
            }

            return $modelAndView;
                        
        }catch(Exception $e){
            
            Phorge::setResponse($response);
            return  $this->exceptionDispatcher->handleException($e, $request, $this->viewDispatcher);
            
        }
    }

   
    /**
     * takes a blockName and returns its rendered view output. Like the getActionOutput
     * method, exceptions thrown during the Block execution and view rendering
     * are caught and rednered into presentation content as well.
     *
     *
     * @param Request $request
     * @param Response $response
     * @param String $blockName
     * @return mixed view output
     */
    public function getBlockOutput(Request $request, Response $response, $blockName){
        try {
            $modelAndView = $this->blockDispatcher->getModelAndView($request, $response, $blockName, $this->moduleName);
            return $modelAndView;
            #$output = $this->viewDispatcher->getViewOutput($modelAndView);
            #return $this->writer->writeBlock($output);
        }catch(Exception $e){
            #Phorge::setBlockResponse($response, $blockName, $this->name);
            return $this->exceptionDispatcher->handleException($e, $request, $this->viewDispatcher);
            #return $this->writer->writeBlock($output);
        }
    }
   
    
    public function renderView(ModelAndView $modelAndView, Request $request){
        try {
            return $this->viewDispatcher->getViewOutput($modelAndView);        
        }catch(Exception $e){
            //this may be a bad idea...
            $modelAndView = $this->exceptionDispatcher->handleException($e, $request, $this->viewDispatcher);
            return $this->viewDispatcher->getViewOutput($modelAndView);        
        }
    }
    

    /**
     * init method is called by the ModuleConfigurer object held by the Phorge (Framework)
     *  super object. This method called upon module invocation and will call
     * DispatcherFactory::getDispatcher() for any dispatcher (Action, Block, View,
     * or Exception) not already defined and populated by IOC.
     *
     * Each dispatcher is also configured using the Dispatcher::configure method.
     *
     */
    public function init(){

        if($this->iocContainer == null){
            $this->iocContainer = Phorge::getIocContainer();
        }
        #look for action, block, view dispatcher
        if(!$this->getActionDispatcher()){
            
            $this->actionDispatcher = DispatcherFactory::getDispatcher(ACTION);
            
        }
        
        $this->actionDispatcher->configure($this);

        
        #=============================================
        if(!$this->getBlockDispatcher()){
            #throw new Exception("BlockDispatcher not defined for module $this->moduleName");
            $this->blockDispatcher = DispatcherFactory::getDispatcher(BLOCK);                    
        }
       
        $this->blockDispatcher->configure($this);

        #=============================================
        
        if(!$this->getViewDispatcher()){            
            $this->viewDispatcher = DispatcherFactory::getDispatcher(VIEW);            
        }
        
        $this->viewDispatcher->configure($this);
        
        #=============================================
        if(!$this->getExceptionDispatcher()){            
            
            $this->exceptionDispatcher = DispatcherFactory::getDispatcher(EXCEPTION);                     
        }       
        
        $this->exceptionDispatcher->configure($this);

        $this->isConfigured = true;
        
        
        if(! $this->writer){
            try {
                $ioc = Phorge::getIocContainer();
                $this->writer = $ioc->getResourceByClass('phorge:core.interfaces.PageWriter');

            }catch(IocException $e){
                $this->writer = new PhorgeWriter();
            }
        }
        
        
        #Logger::debug(Framework::print_rToString($this->actionDispatcher));
        #Logger::debug(Framework::print_rToString($this->viewDispatcher));
        #Framework::logMe($this->properties);
        
    }
    
    


    /**
     * add an ActionFilter object to be invoked in the getActionOutput method
     *
     * The name attribute is optional and can be used to differentiate two
     * filters of the same class type.
     *
     * @param ActionFilter $filter
     * @param filterName $name (optional)
     * @return null
     */
    public function addFilter(ActionFilter $filter, $name = null){
        if(empty($name)){
            $name = get_class($filter);
            if(key_exists($name, $this->filters)){               
               return;
            }

        }
        $this->filters[$name] = $filter;
    }

    /**
     * add an array of ActionFilters.
     *
     * The name used to differentiate each Filter object is derived 1) from
     * the array key value or 2) from the class name of the Filter if that key
     * value is numeric
     *
     * @param array<ActionFilter> $filters
     */
    public function addFilters($filters){
        if(! is_array($filters)){
            throw new Exception("Invalid filter array provided");
        }

        foreach($filters as $name => $filter){
            if(is_numeric($name)){
                $key = get_class($filter);
            }else {
                $key = $name;
            }
            $this->filters[$key] = $filter;
        }
    }

    public function addPathToNamespace($path){
    	PackageManager::addNamespace($this->namespace, $path);
    }
    
    

    public function setProperties(Properties $properties){
        $this->properties = $properties;
    }

    public function getProperties(){
        return $this->properties;
    }

    public function addProperties(Properties $properties){
        $this->properties->combine($properties);
    }

    public function getProperty($key){
        return $this->properties->get($key);
    }

    public function getFilters(){
        return $this->filters;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($moduleName) {
        $this->name = $moduleName;
    }
        
    public function getActionDispatcher() {
        return $this->actionDispatcher;
    }
        
    public function setActionDispatcher(ActionDispatcher $elementDispatcher) {
        $this->actionDispatcher = $elementDispatcher;
    }
        
    public function getBlockDispatcher() {
        return $this->blockDispatcher;
    }
        
    public function setBlockDispatcher(BlockDispatcher $blockDispatcher) {
        $this->blockDispatcher = $blockDispatcher;
    }
        
    
    public function getViewDispatcher() {
        return $this->viewDispatcher;
    }
        
    public function setViewDispatcher(ViewDispatcher $viewDispatcher) {
        $this->viewDispatcher = $viewDispatcher;
    }

    public function getExceptionDispatcher() {
        return $this->exceptionDispatcher;
    }

    public function setExceptionDispatcher($exceptionDispatcher) {
        $this->exceptionDispatcher = $exceptionDispatcher;
    }



    public function getRequest() {
        return $this->request;
    }
        
    public function setRequest($request) {
        $this->request = $request;
    }
        
    public function getResponse() {
        return $this->response;
    }
        
    public function setResponse($response) {
        $this->response = $response;
    }

    

    public function getNamespace() {
        return $this->namespace;
    }

    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    public function getConfigFile() {
        return $this->configFile;
    }

    public function setConfigFile($configFile) {
        $this->configFile = $configFile;
    }
    public function getDirectory() {
        return $this->directory;
    }

    public function setDirectory($directory) {
        $this->directory = $directory;
    }

    public function getDefaultAction() {
        return $this->defaultAction;
    }

    public function setDefaultAction($defaultAction) {
        $this->defaultAction = $defaultAction;
    }


    public function isConfigured(){
        return $this->isConfigured;
    }


    public function getWriter() {
        return $this->writer;
    }
        
    public function setWriter($writer) {
        $this->writer = $writer;
    }

    public function getIocContainer() {
        return $this->iocContainer;
    }

    public function setIocContainer($iocContainer) {
        $this->iocContainer = $iocContainer;
    }

    

    public function diagnostic(){
        $string = "<br>Diagnostic for $this->name module <br>";
        $string .= 'module root directory: ' . $this->getDirectory() . '<br>';
        $string .= 'module namespace: ' . $this->namespace . '<br>';
        $string .= 'module config file: ' . $this->configFile . '<br>';
        $string .= 'module default action: ' . $this->defaultAction . '<br>';
        $string .= 'Action Dispatcher: ' . get_class($this->actionDispatcher) . '<br>';
        $string .= $this->actionDispatcher->diagnostic('---');
        $string .= 'Block Dispatcher: ' . get_class($this->blockDispatcher) . '<br>';
        $string .= $this->blockDispatcher->diagnostic('---');
        $string .= 'View Dispatcher: ' . get_class($this->viewDispatcher) . '<br>';
        $string .= $this->viewDispatcher->diagnostic('---');
        $string .= 'Exception Dispatcher: ' . get_class($this->exceptionDispatcher) . '<br>';
        $string .= 'module writer: ' . get_class($this->writer) .'<br>';
        foreach($this->properties as $key => $value){
            $string .= "--- property $key = $value<br>";
        }


        $string .= '<br>';
        return $string;
    }

}
?>
