<?php
pminclude('phorge:core.interfaces.Block');
class PageHeader implements Block {
	
	public function generateResponse(Request $request, Response $response){
		
		#set some response values:
		$response->put('title', 'Welcome To Phorge');
		$response->put('date', date('m/d/Y h:i:s'));

		return 'PageHeader';
	}
	
}
?>