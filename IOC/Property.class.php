<?php
/** 
 *
 * @author kwinters
 */
pminclude('ioc.Bean');
class Property {
    
    private $name;
    private $value;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getValue() {
        if($this->value instanceof Bean){
            return $this->createObject();
        }
        
        
        
        return $this->value;
    }

    public function setValue($value) {
        
        
        $this->value = $value;
    }


    private function createObject(){
        $bean = $this->value;
        $object = $bean->getInstance();        
        return $object;
    }


}
?>
