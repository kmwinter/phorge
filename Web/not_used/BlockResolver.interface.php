<?php
interface BlockResolver {
	
	public static function resolve($blockName, $moduleName = null);
	
	public static function resolveBlockPath($blockName, $moduleName = null);
	
	
}

?>