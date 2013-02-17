<?php
pminclude('phorge:core.interfaces.Action');
pminclude('phorge:core.UrlService');
pminclude('phorge:core.ModelAndView');

class Bar implements Action {
	
	public function doGet(Request $request, Response $response){
		$response->put('message', 'This message was set in the Action class ' . get_class($this));
		
		/**
		 * returning only a response will tell the framework to use the default view.
		 * The default view has the same name as the action class (Welcome) in this case. 
		 * If there isn't a Welcome view, the framework will look for the default SUCCESS or FAILURE view
		 * depending on whether validation succeeded or failed. 
		 * You can specify a specific view name by returning a ModelAndView object 
		 */  

		return 'success';
	}
	
	
	public function doPost(Request $request, Response $response){

		//without any validation rules to fail, this won't be shown
		$response->put('message', 'You\'ll never see me');
		
		return 'success';
	}
}
?>