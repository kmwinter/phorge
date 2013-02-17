<?php

pminclude('phorge:exceptions.BlockNotFoundException');
pminclude('phorge:core.Framework');
pminclude('phorge:core.ModelAndView');
pminclude('phorge:core.interfaces.BlockDispatcher');


class SimpleBlockDispatcher implements BlockDispatcher {


    private $prefix;
    private $namespace;
    private $iocContainer;


    public function getModelAndView(Request $request, Response $response, $blockName) {

        $class = ($this->namespace? "$this->namespace:" : '') . ($this->prefix? "$this->prefix/": '') . "$blockName";

        try {
            
            $block = $this->iocContainer->getResourceByClass($class);
            Logger::debug("Found block instance $class in ioc configuration" );
        }catch(IocException $e) {

            $blockObjectName = pminclude($class);
            $block = new $blockObjectName($blockName);

        }


        $view = $block->generateResponse($request, $response);
        $modelAndView = new ModelAndView($response, $view);
        #Phorge::setBlockResponse($response, $blockName);

        return $modelAndView;

    }

    public function getPrefix() {
        return $this->prefix;
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }



    public function getNamespace() {
        return $this->namespace;
    }

    public function setNamespace($blockNamespace) {
        $this->namespace = $blockNamespace;
    }


    public function configure(Module $module) {

        if(! $this->getPrefix()) {
            $this->setPrefix(Phorge::getConfigProperty(BLOCK_DIRNAME));
        }

        $this->iocContainer = $module->getIocContainer();
        $this->setNamespace($module->getNamespace());
    }



    protected  function resolve(Request $request, $blockName, $moduleName = null) {


        try {
            $package = $this->resolveBlockPackage($blockName, $moduleName);

            pminclude($package);
            $block = new $blockName($blockName, $moduleName);
            return $block;
        }catch(PackageManagerException $e) {
            try {
                //try without Module
                $package = $this->resolveBlockPackage($blockName, null);
                pminclude($package);
                $block = new $blockName($blockName, $moduleName);
                return $block;

            }catch(PackageManagerException $pe) {
                // let fall to BlockNotFound Exception
            }


        }

        throw new BlockNotFoundException($blockName, $moduleName);

    }

    /**
     * Extention point
     * determine package block
     *
     * @param String $blockName
     * @param String $moduleName = null
     *
     */
    protected function resolveBlockPackage($blockName, $moduleName = null) {
    //$dirname = Framework::getConfigProperty('dirname.blocks');
        if($moduleName) {

            return "modules:$moduleName.$dirname.$blockName";
        }


        return "blocks:$blockName";
    }


    public function diagnostic($prefix = '') {
        $string =  $prefix . 'diagnostic for ' . get_class($this) . '<br>';
        $string .= $prefix . 'prefix: ' . $this->prefix . '<br>';
        $string .= $prefix . 'namespace: ' .$this->namespace . "<br>";
        return $string;
    }


}


?>