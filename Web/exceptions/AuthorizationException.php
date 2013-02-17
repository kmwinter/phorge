<?php
pminclude('phorge:exceptions.AuthException');
class AuthorizationException extends AuthException {
	
	private $userId;
	private $moduleName;
	private $actionName;
	
	public function __construct($userId, $message = null){
		$this->userId = $userId;
		#$this->moduleName = $moduleName;
		#$this->actionName = $actionName;
		if($message){
			$this->message = $message;
		}else {
			$this->message = 'You are not authorized for this Action';
		}
		
			
	}
}

?>