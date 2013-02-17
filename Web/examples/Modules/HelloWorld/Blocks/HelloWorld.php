<?php
pminclude('phorge:core.Block');
class HelloWorld extends Block {
	
	
	protected function generateResponse(Request $request, Response $response){

		$response->put('message', 'Hello World Block');
		
		return new ModelAndView($response, 'BlockView');
		
	}
		
}
?>