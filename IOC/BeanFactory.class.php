<?php

/** 
 *
 * @author kwinters
 */
class BeanFactory {

    private $id;
    private $class;
    private $scope;
    private $bean;
    private $parser;
    private $componentIndex;
    
    public function getBean(){

        //lazy load the Factory's object
        if(! $this->bean){
            $this->bean = $this->parser->getComponentObject($this);
        }


        if($this->scope == 'prototype'){
            return clone($this->instance);
        }        

        if($this->scope == 'session'){
            if(key_exists($this->id, $_SESSION)){
                return $this->bean = $_SESSION[$this->id];
            }
        }
        
        return $this->bean;
    }

    public function setBean($bean){
        $this->bean = $bean;
    }

   
    
    
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }    

    public function getProperties() {
        return $this->properties;
    }

    public function setProperties($properties) {
        $this->properties = $properties;
    }

    public function getScope() {
        return $this->scope;
    }

    public function setScope($scope) {
        $this->scope = $scope;
    }
   
    public function __destruct(){
        $_SESSION[$this->id] = $this->bean;
    }

    public function getParser() {
        return $this->parser;
    }

    public function setParser($parser) {
        $this->parser = $parser;
    }

    public function getComponentindex() {
        return $this->componentIndex;
    }

    public function setComponentIndex($componentIndex) {
        $this->componentIndex = $componentIndex;
    }






    
}
?>
