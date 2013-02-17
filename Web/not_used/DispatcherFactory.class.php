<?php

class DispatcherFactory {
	
		
	public static function getViewer(){
		if(defined('DISPATCHER_VIEWER')){	
			$className = DISPATCHER_VIEWER;	
		}else {
			$className = DEFAULT_DISPATCHER_VIEWER;
		}
		
		return new $className;	
		
		
	}
	
	
}


?>