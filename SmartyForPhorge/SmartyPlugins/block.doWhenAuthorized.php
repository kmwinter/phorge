<?php

function smarty_block_doWhenAuthorized($params, $content, &$smarty, &$repeat){
			
	
	if (isset($content)) {
						
		//is end tag, display form
		$module = $params['module'];
		$action = $params['action'];
		
		
		$role = $params['role'];
		if($params['group']){
			$role = $params['group'];
		}
		
		$username = $params['username'];
		
		$showException = false;
		$showException = $params['showException'];
		try {
			if(! empty($username)){
				if(AuthManager::getAuthenticatedUser()->getUniqueId() === $username){
					return $content;
				}
			}
			
			if(! empty($role)){

				if(AuthManager::authorizeRole($role)){
					return $content;
				}
			}
			
			if(AuthManager::authorizeAction($action, $module)){	
				return $content;
			}
		}catch (AuthException $e){
			if($showException){
				throw $e;
			}
			
		}
	}
}

?>