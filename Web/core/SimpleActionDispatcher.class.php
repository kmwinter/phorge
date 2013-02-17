<?php
/**
 * The SimpleActionDispatcher is the default Action class dispatcher for the Phorge
 * Framework. It takes in the actionName parsed from the UrlMask and uses the configured
 * module properties to translate that to an Action Object. The SimpleActionDispatcher
 * is also responsible for executing that action.
 */

pminclude('phorge:core.Framework');
pminclude('phorge:core.Request');
pminclude('phorge:core.Response');
pminclude('phorge:core.interfaces.ActionDispatcher');
pminclude('phorge:exceptions.ActionNotFoundException');
pminclude('ioc:IocException');

class SimpleActionDispatcher implements ActionDispatcher  {


    private $prefix;
    private $namespace; 
    private $iocContainer;

    /**
     * Uses an actionName parameter to instantiate the action object and return it
     *
     * @param Request $request
     * @param Response $response
     * @param String $actionName
     * @return Action
     */
    
    public function getAction($actionName, Request $request, Response $response){

        $package = ($this->namespace? "$this->namespace:" : '') .  ($this->prefix? "$this->prefix/": '') . $actionName;        
        Logger::trace("trying to find action $actionName, class=$package, prefix=$this->prefix");

        //look in IOC for a reference of the specified action in the IOC container        
        try {                                    
            $action = $this->iocContainer->getResourceByClass($package);
            Logger::debug("Found $package in ioc configuration");
            return $action;
        }catch(IocException $e){                        
            $action = null;
        }

        //Not found in IOC, create new instance from package
        if(! $action){
            try {
                #$action = $this->resolve($actionName, $moduleName);
                $actionObjectName = pminclude($package);
                $action = new $actionObjectName();
                return $action;
            }catch(PackageManagerException $e){
                logger::error("Error looking up action $actionName. Package: $package");
                throw new ActionNotFoundException($actionName);
            }
        }
        
        throw new ActionNotFoundException($actionName);
    }
    
    
    
    /**
     * execute a given action's do[Get/Post] method and return a ModelAndView object
     * 
     * @param String $action
     * @param Request $request
     * @param Response $response
     * @return ModelAndView 
     */
    public function getModelAndView(Action $action, Request $request, Response $response){
        
        $method = $request->getMethod();        
        switch($method){
            case 'GET': $view = $action->doGet($request, $response); break;
            case 'POST': $view = $action->doPost($request, $response);break;
            default: throw new Exception("unkown request method encountered ($method)");
        }
        
        if(!$view){
            throw new Exception("No view returned by Action " . get_class($action));
        }
        
        if(! is_scalar($view)){
            throw new Exception("Invalid view value returned from Action object " . get_class($action));
        }
        
        $modelAndView = new ModelAndView($response, $view);
        Phorge::setResponse($response);
        
        return $modelAndView;


    }

    /*
    protected function resolve($actionName, $moduleName = null){
        
        try {

            $package =  $this->resolveActionPackage($actionName, $moduleName);

            //echo Framework::getConfigProperty(MODULE_DIR) . ' ' .$package;
            //$actionObjectName = $this->resolveActionObjectName($actionName, $moduleName);
            //require_once($path);

            $actionObjectName = pminclude($package);
            $action = new $actionObjectName($actionName, $moduleName);

            return $action;

        }catch(PackageManagerException $e) {

            throw new ActionNotFoundException($actionName, $moduleName);
        }




    }

    

    protected function resolveActionPackage($actionName, $moduleName = null){


        $actionObject = $this->resolveActionObjectName($actionName, $moduleName);

        $dirname = Framework::getConfigProperty('dirname.actions');


        if($moduleName){
            $path = "$this->moduleNamespace:$moduleName.$dirname.$actionObject";
        }else {
            $path = "$this->actionNamespace:$actionObject";
        }
        return $path;
    }

    protected static function resolveActionObjectName($actionName, $moduleName){
        if($moduleName){
            return ucfirst($moduleName) . ucfirst($actionName);
        }
        return ucfirst($actionName);
    }

    */

    public function getPrefix() {
        return $this->prefix;
    }

    public function setPrefix($prefix) {        
        $this->prefix = $prefix;
    }


    public function getNamespace() {
        return $this->namespace;
    }

    public function setNamespace($actionNamespace) {
        $this->namespace = $actionNamespace;
    }

    public function getIocContainer() {
        return $this->iocContainer;
    }

    public function setIocContainer(IOCContainer $iocContainer) {
        $this->iocContainer = $iocContainer;
    }





    public function configure(Module $module){
        
        if(!$this->getPrefix()){
            $this->setPrefix($module->getProperty(ACTION_DIRNAME));
        }

        $this->setNamespace($module->getNamespace());
        $this->iocContainer = $module->getIocContainer();
    }


    public function diagnostic($prefix = ''){
        $string =  $prefix . 'diagnostic for ' . get_class($this) . '<br>';
        $string .= $prefix . 'prefix: ' . $this->prefix . '<br>';
        $string .= $prefix . 'namespace: ' . $this->namespace . "<br>";
        //PackageManager::printNamespaces();
        $namespaces = PackageManager::getNamespaces();
        $string .= $prefix . 'resolved namespace path: ';
        foreach($namespaces[$this->namespace] as $paths) {
            $string .= $path . ' ';
        }
        $string .= "<br/>";
        return $string;
    }


}


?>