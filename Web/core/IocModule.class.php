<?php


pminclude('phorge:core.Module');
pminclude('lib:IOC.IocContainer');
class IocModule extends Module {
   
    private $xmlFile = 'ioc.xml';
    

    public function init(){
        
        $xmlPath = rtrim(parent::getDirectory(), '/') .  "/{$this->xmlFile}";
        
        if(! file_exists($xmlPath)){
            
            throw new Exception("XML config file specified for IocModule {$this->name}" .
                  " does not exist ($xmlPath)");
        }

        if(! parent::getDefaultAction()){
            $this->defaultAction = Framework::getConfigProperty(INDEX_ACTION);
        }
        

        $ioc = new IocContainer();
        $ioc->addConfigProperties(Phorge::getConfigProperties());
        $ioc->setConfigProperty('ioc.xml.file', $xmlPath);

        $this->iocContainer = $ioc;
        #$parser = $ioc->getParser();
        #print_r($parser);
        #print_r($parser->getFactories());
        
        try {
            $dispatcher = $ioc->getResourceByClass('phorge:core.interfaces.ActionDispatcher');            
            parent::setActionDispatcher($dispatcher);            
        }catch(IocException $e){               
        }
        try {
            parent::setViewDispatcher($ioc->getResourceByClass('phorge:core.interfaces.ViewDispatcher'));
        }catch(IocException $e){                        
        }
        try {
            parent::setBlockDispatcher( $ioc->getResourceByClass('phorge:core.interfaces.BlockDispatcher'));
        }catch(IocException $e){            
        }    
        
        try {
            parent::setExceptionDispatcher($ioc->getResourceByClass('phorge:core.interfaces.ExceptionDispatcher')); 
        }catch(IocException $e){            
        }

        
        
        $filters = $ioc->getAllResourcesByClass('phorge:core.interfaces.ActionFilter');
        foreach($filters as $name => $filter){
            $this->addFilter($filter);
        }

        parent::init();


    }

    public function getXmlFile() {
        return $this->xmlFile;
    }
        
    public function setXmlFile($xmlFile) {
        $this->xmlFile = $xmlFile;
    }

    



    
    
    
}
?>
