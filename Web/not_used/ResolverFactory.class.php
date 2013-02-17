<?php

class ResolverFactory {
	
	
	public static function getResolver($type, $moduleName = null){
		//TODO allow for module specific resolvers?
		
		switch($type){
			case ACTION: $className = ACTION_RESOLVER;
							return new $className;
			case VIEW: $className = VIEW_RESOLVER;
							return new $className;
			case BLOCK: $className = BLOCK_RESOLVER;
							return new $className;
			case EXCEPTION_RESOLVER: $className = EXCEPTION_RESOLVER;
							return new $className;				
		}								

		throw new GeneralException("Invalid Resolver Type");	
	}
	

}


?>