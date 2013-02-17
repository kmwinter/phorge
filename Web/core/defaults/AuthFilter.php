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
class AuthFilter implements ActionFilter {
    
    
    public function executePreFilter(Action $action, Request $request, Response $response){
    
        if($action instanceof Authorizing){
            $action->authorize($request);
        }
        
        
    }
    
    public function executePostFilter(Action $action, Request $request, ModelAndView $modelAndView){
        return null;
    }
}
?>
