<?php
class DefaultBlockResolver implements BlockResolver {
	
	
	public static function resolve($blockName, $moduleName = null){
		$path = self::resolveBlockPath($blockName, $moduleName);
		if(!file_exists($path)){	
			throw new BlockNotFoundException($blockName, $moduleName);	
		}
		
	
		if($moduleName){
			$objectName = ucfirst($blockName); //. ucfirst($moduleName);
		}else {
			$objectName = ucfirst($blockName);
		}
		global $request;
		require_once($path);
		
		$block = new $objectName($request, $blockName);
		

		return $block;
	
		
	}
	
	public static function resolveBlockPath($blockName, $moduleName = null){
		if($moduleName){
			$blockObject = ucfirst($moduleName) . ucfirst($blockName); 
			return MODULE_ROOT . "/$moduleName/$blockObject.php";
		}else {
			
			$blockName = ucfirst($blockName);
			return BLOCK_ROOT . "/$blockName.php";
		}
		
	}
	
	
}