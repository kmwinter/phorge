<?php
//class meant to just absorb function calls. 
//only used when Pear::Log not available

pminclude('phorge:core.interfaces.PhorgeLogger');


class NullLogger implements PhorgeLogger {
	
	
	
	
	public function __construct(){		
	}
	
	public function trace($message){
	
	}
	
	public function debug($message){					
	
	}
	
	public function notice ($message){
	
	}
	
	public function warn($message){
	
	}
	
	public function error($message){
	
	}
	
	public function log($message, $level = null){		
	
				
	}
	
	public function getMessages(){
	
	}
	
	public function transformTime($time){
	
	}
	
	public function getLogOutput($newLine = "\n"){				        
            return null;
	}
	
}

?>