<?php

function smarty_function_isAuthorized($params, &$smarty){
			

			
		
		//is end tag, display form
		$module = $params['module'];
		$action = $params['action'];
		$role = $params['role'];
		if($params['group']){
			$role = $params['group'];
		}
		$username = $params['username'];
		$assign = $params['assign'];
		
		
		$showException = false;
		$showException = $params['showException'];
		try {
			if(! empty($username)){
				if(AuthManager::getAuthenticatedUser()->getUniqueId() === $username){
										
					$smarty->assign($assign, 1);
					
					#return 1;
				}
			}
			
			if(! empty($role)){

				if(AuthManager::authorizeGroup($role)){
					$smarty->assign($assign, 1);

					#return 1;
				}
			}
			
			if(AuthManager::authorizeAction($action, $module)){	
				$smarty->assign($assign, 1);
				#return 1;
			}
		}catch (Exception $e){
			if($showException){
				throw $e;
			}
			
		}
		
		return false;
	
}

?>