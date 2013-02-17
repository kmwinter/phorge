<?php

//Logging Service
#pminclude('phorge:core.interfaces.FrameworkLogger');
pminclude('phorge:core.defaults.NullLogger');

class Logger {

	private static $log;
	
		
	public static function getInstance(){
            if(!self::$log){
            
                try {
                    $ioc = Phorge::getIocContainer();

                    self::$log = $ioc->getResourceByClass('phorge:core.interfaces.PhorgeLogger');

                }catch(IocException $e){
                    self::$log = new NullLogger();
                }
            
			
            }
		
            return self::$log;
	}


        public static function getLogger($className = null){
            //do this so a class can have its own log object so it can log its classname...
        }

	public static function setLogger(PhorgeLogger $log){
		self::$log = $log;
	}
	
	
	public static function trace($message){
		self::getInstance()->trace($message);			
	}
	
	public static function debug($message){
		self::getInstance()->debug($message);
	}
	
	public static function notice ($message){
		self::getInstance()->notice($message);
	}
	
	public static function warn($message){
		self::getInstance()->warn($message);
	}
	
	public static function error($message){
		self::getInstance()->error($message);
	}
	
	public static function log($message, $level = null){
		self::getInstance()->log($message, $level);		
	}

    public static function initialize(){
        self::getInstance();
    }
	


}



?>