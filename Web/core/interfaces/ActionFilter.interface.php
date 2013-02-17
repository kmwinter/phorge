<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RequestFilter.interface
 *
 * @author kwinters
 */
interface ActionFilter {

    
    public function executePreFilter(Action $action, Request $request, Response $response);
    public function executePostFilter(Action $action, Request $request, ModelAndView $modelAndView);
    
}
?>
