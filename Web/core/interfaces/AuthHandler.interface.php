<?php

interface AuthHandler {
	
	public function authorizeAction($actionName, $moduleName = null); //$module, $action = null);
	public function authorizeUser($uniqueId);
	public function authorizeRole($roleId);
	public function login($userId, $password);
	public function logout();
	public function isAuthenticated();
	public function getAuthenticatedUser();    		
}


?>