<?php
pminclude('phorge:core.interfaces.Block');
class Log implements Block {
	
	public function generateResponse(Request $request, Response $response){
		
		#only works with Framework's Default Logger
		if(Framework::getConfigProperty(DEBUG) == 'true'){
			$messages = Logger::getInstance()->getMessages();
			
		}else {
			$messages = array();
		}
		
		$response->put('messages', $messages);
		$response->put('date_format', date('m/d/Y h:i:s'));

		return 'log';
	}
	
}
?>