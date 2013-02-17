<?php

/**
 * Basic implementation of the ViewDispatcher interface. Will take
 * a string representation of a view (via ModelAndView->getView()), append the
 * $appendValue to it and try to find that file in the specified $viewDirectory.
 *
 * Once the viewPath is identified, that file is included and its contents 
 * returned by the getViewOutput method
 *
 *
 * View Directory is stored as an array of values so it will continue to loop
 * through the provided directories until it finds a matching file. If no file
 * is found a ViewNotFoundException is trhown.
 *
 * If no $viewDirectory is specified before the configure method is called, this
 * will default to the combination of the module's base directory and the value
 * specified by the dirname.modules config property.
 *
 * if the appendValue is not specified before the configure method is called,
 * this will default to .php
 *
 *
 * @author kwinters
 */

pminclude('phorge:core.interfaces.ViewDispatcher');
pminclude('phorge:exceptions.ViewNotFoundException');
class SimpleViewDispatcher implements ViewDispatcher {
    
    private $viewDirectory = array();
    private $appendValue;
    

    /**
     * Takes a ModelAndView object and translates that into a view file. Then
     * returns that file's rendered contents. 
     *
     * @param ModelAndView $modelAndView
     * @return String rendered view content
     */
    public function getViewOutput(ModelAndView $modelAndView){
                
        $view = $modelAndView->getView();
        $viewPath = $this->resolveViewPath($view);
        $viewName = $modelAndView->getView();
        
        if(strstr($viewName, '/')){
            $viewName = ltrim(strstr($viewName, '/'), '/');
        }
        
        global $$viewName;
        $$viewName = $modelAndView->getResponse();        
        $result = Phorge::getIncludeFileContent($viewPath);
        unset($$viewName);
        return $result;
        
        
    }

    /**
     * This method resolves the file path for a given view name by cycling
     * through the view paths held in the viewDirectory class property.
     *
     * If no matching file is found for a particular view name then a
     * ViewNotFoundException is thrown.
     *
     * @param String $view
     * @return String view file path
     */
    public function resolveViewPath($view){
        if(! is_scalar($view)){
            throw new Exception("non scalar view value passed into simpleViewDispatcher->resolveViewPath");
        }

        if(! $this->viewDirectory){
            throw new Exception("No view directories defined in ViewDispatcher");
        }
       
        foreach($this->viewDirectory as $directory){
            
            $path =  rtrim($directory, '/') . "/" . $view . $this->appendValue;         
            if(file_exists($path)){
                return $path;
            }
        }

        throw new ViewNotFoundException($path);


    }


   
    /**
     * Called by the ModuleConfigurer the first time this module is accessed.
     * Will configure the defaults for this class.
     *
     * @param Module $module
     *
     */
    public function configure(Module $module){
        
        $viewDir = $module->getDirectory() . '/' . $module->getProperty(VIEW_DIRNAME);
        $this->addViewDirectory($viewDir);

        if(! $this->getAppendValue()){
            $this->setAppendValue(Phorge::getConfigProperty(DEFAULT_VIEW_EXTENSION));
        }
    }
    
    public function getAppendValue() {
        return $this->appendValue;
    }
        
    public function setAppendValue($appendValue) {
        $this->appendValue = $appendValue;
    }
        
    
    public function getViewDirectory() {
        return $this->viewDirectory;
    }

    public function setViewDirectory($viewDirectory) {
        if(!is_array($viewDirectory)){                 
            $viewDirectory = array($viewDirectory);            
        }
        
        $this->viewDirectory = $viewDirectory;        
    }

    public function addViewDirectory($viewDirectory, $addToTop = true){
        
        if($addToTop){
           array_unshift($this->viewDirectory, $viewDirectory);
        }else {
            $this->viewDirectory[] = $viewDirectory;
        }
    }

    public function diagnostic($prefix = ''){
        $string =  $prefix . 'diagnostic for ' .  get_class($this) . '<br>';
        $string .= $prefix . 'append value: ' .$this->appendValue . '<br>';
        foreach($this->viewDirectory as $directory) {
            $string .= $prefix . 'view directory: ' . $directory . '<br>';
        }
        return $string;

    }




}
?>
