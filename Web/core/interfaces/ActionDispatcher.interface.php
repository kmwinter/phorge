<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActionDispatcher
 *
 * @author kwinters
 */
pminclude('phorge:core.interfaces.Dispatcher');
interface ActionDispatcher extends Dispatcher {

    public function getAction($actionName, Request $request, Response $response);
    public function getModelAndView(Action $action, Request $request, Response $response);
    public function setNamespace($namespace);
    public function getNamespace();

    
}
?>
