<?php
pminclude('phorge:core.Action');
pminclude('phorge:core.UrlService');

class WelcomeDefault extends Action {
	
	
	protected function doWhenValid(Request $request, Response $response){
		$response->put('message', 'This message was set in the Action class');
		
		return $response;
	}
	
	protected function doWhenInvalid(Request $request, Response $response){
		//without any validation rules, this won't be shown
		$response->put('message', 'Go away!');
		
		return $response;
	}
}
?>