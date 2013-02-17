<?php

/**
 *
 * @author kwinters
 */
class IocConfig {  

    private $properties = array();


    public function __set($name, $value){
        $this->properties[$name] = $value;
    }

    public function __get($name){
        if(key_exists($name, $this->properties)){
            return $this->properties[$name];
        }

        return null;
    }

    public function getProperties(){
        return $this->getProperties;
    }

}
?>
