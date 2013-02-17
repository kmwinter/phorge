<?php
pminclude('phorge:core.interfaces.UrlMask');
pminclude('phorge:core.ActionUrl');
pminclude('phorge:core.Url');

class UrlService {
	
	#singleton instance of UrlMask specified in config
	private static $mask;
        
	private static function getMask(){
		if(!self::$mask){
            try {
                $ioc = Phorge::getIocContainer();
                $mask = $ioc->getResourceByClass('phorge:core.interfaces.UrlMask');
                self::$mask = $mask;
            }catch(IocException $e){
                Logger::trace("creating default UrlMask");

                $maskName = pminclude(Framework::getConfigProperty(URL_MASK));
                self::$mask = new $maskName();
            }
			
		}
		
		return self::$mask;
	}

	/*public static function getUrl($module, $action = null, $id = null, $properties = array(), $subAction = null){
		return self::getUrlString($module, $action, $properties, $subAction);
	}*/

    
	
	public static function getUrlString(Url $url){
					
		# don't pass in url object because calling url->getString() within UrlMask->getUrlString 
		# will create an infinite loop
		return self::getMask()->getUrlString($url->getPath(),$url->getParams(), $url->getAnchor());
		#print_r(self::getMask());
		#return "url string";
		
		
	}
	
	
	public static function getActionUrlString(ActionUrl $url){
					
		# don't pass in url object because calling url->getString() within UrlMask->getUrlString 
		# will create an infinite loop
		return self::getMask()->getActionUrlString($url->getAction(), $url->getModule(),$url->getId(),
								$url->getParams(), $url->getAnchor());
		#print_r(self::getMask());
		#return "url string";
		
		
	}
	
	public static function getCurrentUrl(){
		$request = Framework::getRequest();
		return new ActionUrl($request->get(ACTION), $request->get(MODULE), $request->get(ID), $request->getGetProperties());
	}
	
	public function parse(){		
		self::getMask()->parse(Framework::getRequest());
	}
	
	public static function getModule(){
	
		return self::getMask()->getModule();
	}
	
	
	public static function getAction(){
		return self::getMask()->getAction();
	}
	
	
	
	
	
	
	
	/*public static function getMask($maskName = null){
		
		$className = URL_MASK;
		$object = new $className;
		
		$maskClass = ucfirst($maskName) . 'UrlMask';
		if(file_exists(CUSTOM_LIB . "/$maskClass.php")){
			//return $maskClass::getUrl($locaton, $module, $action, $subAction);
		}
		return DefaultUrlMask::getUrl($locaton, $module, $action, $subAction);
	}*/
}

?>