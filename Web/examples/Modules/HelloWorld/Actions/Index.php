<?php
pminclude('phorge:core.interfaces.Action');

class Index implements Action {
	
	
	public function doGet(Request $request, Response $response){

		$response->put('message', 'Hello World');
		
		return 'HelloWorld';
	
	}

    public function doPost(Request $request, Response $response){
        return $this->doGet($request, $response);
    }
		
}


?>