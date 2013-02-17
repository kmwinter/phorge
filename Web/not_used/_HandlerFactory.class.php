<?php

class HandlerFactory {
	
	
	public static function getHandler($type){
		
		switch($type){
			case MODULE: $className = ACTION_HANDLER;
							return new $className;
			case ACTION: $className = ACTION_HANDLER;
							return new $className;							
			case BLOCK: $className = BLOCK_HANDLER;
							return new $className;
			case EXCEPTION: $className = EXCEPTION_HANDLER;
							return new $className;				
		}								

		throw new GeneralException("Invalid Handler Type");	
	}
	

}


?>