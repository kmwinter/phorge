<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewDispatcher
 *
 * @author kwinters
 */
pminclude('phorge:core.interfaces.Dispatcher');
interface ViewDispatcher extends Dispatcher {
    //put your code here

    public function getViewOutput(ModelAndView $modelAndView);
    
}
?>
