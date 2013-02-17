<?php
pminclude('phorge:exceptions.AuthException');
class AuthenticationException extends AuthException {
	
	private $userId;
	
	public function __construct($message, $userId = ''){
		$this->userId = $userId;	
		$this->message = $message; 
	}
}

?>