<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidationRule
 *
 * @author kwinters
 */
interface ValidationRule {
   	public function validate(Request $request, $property, $options);
}
?>
