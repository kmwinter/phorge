<?php

class enum {
    private $__this = array();
  
  function __construct() {
        $args = func_get_args();
        $i = 0;
        do{
            $this->__this[$args[$i]] = $i;
        } while(count($args) > ++$i);

    }

    public function __get($n){
        return $this->__this[$n];
    }
  
    public function label($var){
  	foreach($this->__this as $label => $value){
            if($value === $var){
                return $label;
            }
        }

        throw new Exception("Invalid enum value: $var ");
    }

    public function value($label){
        foreach($this->__this as $key=> $value){
            if($label === $key){
                return $value;
            }
        }

        throw new Exception("Invalid enum label: $label");
    }
  
};

?>