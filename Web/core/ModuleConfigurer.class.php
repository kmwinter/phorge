<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModuleConfigurer.class
 *
 * @author kwinters
 */

pminclude('phorge:core.Module');
class ModuleConfigurer {


    private $moduleRoot;
    private $defaultModuleName = DEFAULT_VALUE;
    private $modules = array();
    private $globalFilters;




    public function getModuleRoot() {
        return $this->moduleRoot;
    }

    public function setModuleRoot($moduleRoot) {
        $this->moduleRoot = $moduleRoot;
    }

    public function getDefaultModuleName() {
        return $this->defaultModuleName;
    }

    public function setDefaultModuleName($defaultModule) {
        $this->defaultModuleName = $defaultModule;
    }



    public function getModules() {
        return $this->modules;
    }

    public function setModules($modules) {
        $this->modules = $modules;
    }


    public function addGlobalFilters($filters){
        $this->globalFilters = $filters;
    }

    public function getModule($moduleName){

        if($moduleName == null){
            $moduleName = $this->defaultModuleName;
        }

        if(! key_exists($moduleName, $this->modules)){
            throw new Exception("Module $moduleName was not found");
        }
        $module = $this->modules[$moduleName];

        
        if(! $module->isConfigured()){
            
            $this->configureModule($module);
        }

        return $module;

    }

    public function addModule(Module $module){
        if($module->getName() == null){
            throw new Exception("encountered Module with no moduleName");
        }
        $this->modules[$module->getName()] = $module;
    }

    public function moduleExists($moduleName){
        return key_exists($moduleName, $this->modules);
    }

    public function getModuleList(){
        return array_keys($this->modules);
    }


    public function hasDefaultModule(){
        return $this->moduleExists($this->defaultModuleName);
    }


    public function configure(){

        $this->loadModules();
        foreach($this->modules as $module){
            //create module namespace
            //required so that you can declare components in these modules
            //in ioc config
            PackageManager::addNamespace($module->getName(), $module->getDirectory());

        }


    }

    private function configureModule($module){
        
        $moduleName = $module->getName();

        $module->setProperties(Phorge::getConfigProperties());

        if(! $module->getDirectory()){            
            $module->setDirectory($this->moduleRoot . "/$moduleName");
        }

        if($module->getConfigFile()){

            #look for specified config file
            $fullConfigPath = $module->getDirectory() . '/' . $module->getConfigFile();

            if(file_exists($fullConfigPath)){
                Logger::Debug("Running config script for Module $moduleName");
                require $fullConfigPath;
            }else {

                Logger::warn("Module $moduleName config file does not exist");
            }

        }else {
            #look for default module init script
            $scriptName = Phorge::getConfigProperty(DEFAULT_MODULE_SCRIPT);
            $fullConfigPath = rtrim($module->getDirectory(), '/') . '/' . $scriptName;
            if(file_exists($fullConfigPath) && ! is_dir($fullConfigPath)){
                $module->setConfigFile($scriptName);
                Logger::Debug("Running default config script for Module $moduleName");
                require $fullConfigPath;
            }

        }

        
        
        
        //if not configured, set namespace to moduleName
        if(!$module->getNamespace()){
            $module->setNamespace($moduleName);
        }

        PackageManager::addNamespace($module->getNamespace(), $module->getDirectory());

        if(! $module->getDirectory()){
            throw new Exception("Directory null for Module $module->getname())");
        }
        
        if(! $module->getdefaultAction()){
            $module->setdefaultAction(Framework::getConfigProperty(INDEX_ACTION));
        }

        //add global Filters
        foreach($this->globalFilters as $name => $filter){
            $module->addFilter($filter);
        }
        
        $module->init();


    }


    public function loadModules(){

        if(! $this->moduleRoot){
            throw new Exception("Can not load module list because moduleRoot is null");
        }

        $directory = $this->moduleRoot;

        if (is_dir($directory)) {
            if ($dh = opendir($directory)) {
                while (($file = readdir($dh)) !== false) {
                    //if( in_array(trim(strrchr($file, '.'), '.'), $fileTypesArray)){
                    if(! ($file == '.' || $file=='..' || substr($file, 0, 1) == '.') ){

                        #$files[] = $file;
                        $path = $directory . "/$file";
                        if(is_dir($path)){
                            //don't overwrite pre-defined modules
                            if(! key_exists($file, $this->modules)){
                                $module = new Module();
                                $module->setName($file);
                                $module->setDirectory($path);
                                $this->modules[$file] = $module;

                            }

                        }

                    }

                }

                closedir($dh);

            }else {
                throw new Exception("Could not open directory $directory for reading");

            }
        }else 	{
            throw new Exception("Not a directory: $directory");
        }


        #merge
        #array_merge($this->modules, $modules);

    }


}
?>
