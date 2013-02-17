<?php
pminclude('phorge:exceptions.GeneralException');
/**
 * DispatcherFactory will resolve IOC/default version of a particular dispatcher
 * given a type key.
 *
 * So for a given Type (e.g. Action), this class will first look in the
 * IOC container for a bean of that type. If one is not present, it will return
 * the defaut (unconfigured) dispatcher defined in the config.properties file
 *
 */
class DispatcherFactory {
	
	/*
     * Return Dispatcher defined in A) the IocContainer or B) default dispatcher
     * defined in config.properties for a given TYPE     
     */	
    public static function getDispatcher($type){
        
        try {
            return self::getPrimaryDispatcher($type);
        }catch(IocException $e){                  
            Logger::trace("returning default dispatcher for type $type");
            return self::getDefaultDispatcher($type);
        }
    }
    
   
    private static function getPackages($type){
		switch($type){			
			case ACTION: 
            
                 return array('interface' => 'phorge:core.interfaces.ActionDispatcher',                         
                              'default'=> Phorge::getConfigProperty(DEFAULT_ACTION_DISPATCHER));							
			case BLOCK: 
                return array('interface' => 'phorge:core.interfaces.BlockDispatcher',    
                             'default'=> Phorge::getConfigProperty(DEFAULT_BLOCK_DISPATCHER));
			case EXCEPTION: 
                return array('interface' => 'phorge:core.interfaces.ExceptionDispatcher',    
                             'default'=> Phorge::getConfigProperty(DEFAULT_EXCEPTION_DISPATCHER));
			case VIEW: 
                return array('interface' => 'phorge:core.interfaces.ViewDispatcher',    
                             'default'=> Phorge::getConfigProperty(DEFAULT_VIEW_DISPATCHER));   
		}								

		throw new GeneralException("Invalid Dispatcher Type");	
	}
    
    
    public static function getPrimaryDispatcher($type){
        
        $packages = self::getPackages($type);
        $interface = $packages['interface'];        
        $ioc = Phorge::getIocContainer();        
        $dispatcher = $ioc->getResourceByClass($interface);        
        return clone($dispatcher);
    }
    
    
    public static function getDefaultDispatcher($type){
        $packages = self::getPackages($type);
        $default = $packages['default'];        
        $className = pminclude($default);
        $dispatcher = new $className();
        return clone($dispatcher);
    }

}


?>