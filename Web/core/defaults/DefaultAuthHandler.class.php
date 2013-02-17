<?php
pminclude('phorge:core.defaults.DefaultUser');
pminclude('phorge:core.interfaces.AuthHandler');
pminclude('phorge:exceptions.AuthenticationException');
pminclude('phorge:exceptions.AuthorizationException');

class DefaultAuthHandler implements AuthHandler {
	
	const SESSION_USER_KEY = '_phorge_user';
	const ADMIN_ROLE = 'admin';
	
        
	/**
	 * Simplistic actionAuth procedure checks to see if actionName is 
	 * one of those specified in the Framework's config.properties 
	 * (default are Add,Edit,Delete)
	 * 
	 * @param String $actionName 
	 * @param String $moduleName
	 * @throws AuthorizationAction,AuthenticationException
	 *  
	 */
    
	public function authorizeAction($actionName, $moduleName = null){
		
		$protectedActions = split(',', Framework::getConfigProperty(PROTECTED_ACTIONS));
		if(in_array($actionName, $protectedActions)){
			
			$user = $this->getAuthenticatedUser();
		
			if($user->hasGroup(self::ADMIN_ROLE)){
				return true;							
			}
			
			throw new AuthorizationException($user->getUniqueId());
		}
		
		return true;
	}
	
	/**
	 * checks the provided $uniqueId against the user stored in the session
	 * 
	 * @param String $uniqueId
	 * @throws AuthorizationException,AuthenticationException
	 */
	public function authorizeUser($uniqueId){
		
		$user = $this->getAuthenticatedUser();
						
		 if($uniqueId === $user->getUniqueId()){
		 	return true;
		 }
		 
		 throw new AuthorizationException($user->getUniqueId());
	}
	
	/**
	 * checks the provided $roleId against those of the user stored in the session
	 * 
	 * @param String $roleId
	 * @throws AuthenticationException,AuthorizationException
	 */
	public function authorizeRole($roleId){
		$user = $this->getAuthenticatedUser();
		
		if($user->hasRole($roleId)){
			return true;
		}
		
		throw new AuthorizationException($user->getUniqueId());
	}
	
	
	/**
	 *  
	 * Extention Point. 
	 * This is where you should check provided user credentials against
	 * your user data store.
	 * 
	 * @param String $uniqueId
	 * @param String $password
	 */
	protected function authenticate($uniqueId, $password){
		
		if($uniqueId === Framework::getConfigProperty(AUTH_UNIQUE_ID) &&
			$password === Framework::getConfigProperty(AUTH_PASSWORD)){
				$user = new DefaultUser($uniqueId, array(self::ADMIN_ROLE), 'No Name');				
				return $user;
		}
		
		
		throw new AuthenticationException("Invalid uniqueId or password");
		
		
	}
	
	
	/**
	 * stores authenticated user in the session
	 * returns false if authentication fails. true otherwise	 
	 * 
	 * @param String $uniqueId
	 * @param String $password
	 * @return boolean
	 *  
	 * 
	 */
	public function login($userId, $password){
		
		$user = $this->authenticate($userId, $password);
		
		$session = Framework::getSession();								
		$session->put(self::SESSION_USER_KEY, $user);
		
		return true;
	}
	
	
	/**
	 * removes user from session
	 * @return boolean
	 * 
	 */
	public function logout(){
		$session = Framework::getSession();
		$session->clearProperty(self::SESSION_USER_KEY);
		return true;
	}
	
	/**
	 * @return boolean true if user is found in session, false otherwise
	 */
	public function isAuthenticated(){
		$session = Framework::getSession();
		$user = $session->get(self::SESSION_USER_KEY);
		 
		if($user){
			return true;
		}
		
		return false;
		
	}
	
	/**
	 * returns the user stored in the session. if no user is in session, false is returned
	 * 
	 * @return PhorgeUser user
	 * @throws AuthenticationException
	 */
	public function getAuthenticatedUser(){		
		if(! $this->isAuthenticated()){
			throw new AuthenticationException("User is not logged in");
		}
		
		$session = Framework::getSession();
		return $session->get(self::SESSION_USER_KEY); 
	}
	
}


?>