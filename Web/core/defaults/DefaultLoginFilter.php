<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultLoginFilter
 *
 * @author kwinters
 */

pminclude('phorge:core.interfaces.ActionFilter');
pminclude('phorge:core.AuthManager');
class DefaultLoginFilter implements ActionFilter {
    
    const LOGIN_PARAMNAME = '_login_username';
    const PASSWORD_PARAMNAME = '_login_password';
    
    /*
     * RequestFilter functions
     * 
     */
    public function executePreFilter(Action $action, Request $request, Response $response){
        
        //populate login keys (for use in view)
        $response->put('login_key', self::LOGIN_PARAMNAME);
        $response->put('password_key', self::PASSWORD_PARAMNAME);
        
        //look for login:
        if($request->containsKey(self::LOGIN_PARAMNAME)){            
            Logger::notice("Login found");
            $userId = $request->get(self::LOGIN_PARAMNAME);
            $password = $request->get(self::PASSWORD_PARAMNAME);

            AuthManager::login($userId, $password);
            
            $request->setMethod('GET');
            
        }
        
        
        
    }
    
    public function executePostFilter(Action $action, Request $request, ModelAndView $modelAndView){
        return null;
    }
}
?>
