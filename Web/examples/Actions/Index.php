<?php
pminclude('phorge:core.interfaces.Action');

class Index implements action {
	
	public function doGet(Request $request, Response $response){		
		
        return Phorge::forward($request, $response, 'Welcome');
	}
    
    public function doPost(Request $request, Response $response){
        return $this->doGet($request, $response);
    }

}
?>