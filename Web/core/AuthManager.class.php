<?php


/**
 * static wrapper for AuthHandler class
 * allows for custom object that adhears to ActionHandler interface
 * 
 */

class AuthManager {
	
	//private $authManager = null;
	private static $instance;
		
	static  function  getInstance()  {
        
       if(!self::$instance)  {
           
           try {
               
               $ioc = Phorge::getIocContainer();
               $authHandler = $ioc->getResourceByClass('phorge:core.interfaces.AuthHandler');               
               self::$instance = $authHandler;
           } catch(IocException $e){
               
               $authHandlerClass = pminclude(Framework::getConfigProperty(AUTH_HANDLER));
               self::$instance = new $authHandlerClass;
           }           
       }  
       
       return  self::$instance;             
   }

	public static function authorizeAction($actionName, $moduleName = null){
		return self::getInstance()->authorizeAction($actionName, $moduleName);
	}
	

	public static function authorizeUser($uniqueId){
		return self::getInstance()->authorizeUser($uniqueId);
	}
    
	public static function authorizeRole($roleId){       
		return self::getInstance()->authorizeRole($roleId);
	}

    public static function isAuthorized($role){
        try {
            self::authorize($role);
            return true;
        }catch(AuthException $e){
            return false;
        }
    }
	
	
	public static function login($uniqueId, $password){
		return self::getInstance()->login($uniqueId, $password);
	}
	
	public static function logout(){
		return self::getInstance()->logout();
	}
	
	
	public static function isAuthenticated(){
		return self::getInstance()->isAuthenticated();
	}
	
	public static function getAuthenticatedUser(){
		return self::getInstance()->getAuthenticatedUser();
	}

    //just an excuse to initialize the authHandler
	public static function initialize(){
        self::getInstance();
    }
	
}



?>