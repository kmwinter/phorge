<?php

class BlockHandlerFactory {
	
	public static function getHandler(){
		if(! defined('BLOCK_HANDLER')){
			throw new GeneralException('BLOCK_HANDLER not defined');
		}
		
		$className = BLOCK_HANDLER;
		
		return new $className;
		
	}
	
}

?>