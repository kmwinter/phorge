<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidatorErrors.class
 *
 * @author kwinters
 */

class ValidatorErrors {
    private $errors = array();
    
    
    public function add($error){
        if(! is_array($error)){
            throw new Exception('Error must be of type Array');
        }
        if(! empty($error)){
            $this->errors[$error[Validator::PROPERTY]] = $error[Validator::MESSAGE];
        }
    }

    public function addMessage($property, $message){
        $this->errors[$property] = $message;
    }
    
    public function getErrors() {
        return $this->errors;
    }
        
    public function hasErrors(){
        
        return count($this->errors) > 0;
    }
    
    public function hasError($property){
        return key_exists($property, $this->errors);
    }
    
    public function getError($property){
        return $this->errors[$property];
    }
    
        

    
    
}
?>
