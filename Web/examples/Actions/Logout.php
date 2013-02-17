<?php
pminclude('phorge:core.interfaces.Action');
pminclude('phorge:core.AuthManager');
pminclude('phorge:core.ModelAndView');

class Logout implements Action {
	
	public function doGet(Request $request, Response $response){
		if(AuthManager::isAuthenticated()){
			AuthManager::logout();
			$response->put(MESSAGE, 'You have been logged out');
            return 'success';
		}else {
            $response->put(MESSAGE, 'you are not logged in!');
            return 'failure';
        }		
	}


    public function doPost(Request $request, Response $response){
        return $this->doPost($request, $response);
    }

}


?>