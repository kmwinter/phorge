<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthExample
 *
 * @author kwinters
 */

pminclude('phorge:core.interfaces.Action');
pminclude('phorge:core.AuthManager');
class Login implements Action{    
    //put your code here
    
    public function doGet(Request $request, Response $response){
        //this will force a login
        AuthManager::authorizeRole('admin');
        $response->put('message', 'Success!');
        return 'authorized';

    }
    
    public function doPost(Request $request, Response $response){

        return $this->doGet($request, $response);

    }
    
}
?>
