<?php

class DefaultActionResolver implements ActionResolver  {
	
	public function resolve($moduleName, $actionName, Request $request){
		
		if(file_exists(self::resolveActionPath($moduleName, $actionName))){
			//create specified action


			$path =  self::resolveActionPath($moduleName, $actionName);
			$actionObjectName = self::resolveActionObjectName($moduleName, $actionName);
			require_once($path);
			$action = new $actionObjectName($request, $actionName);
			
			
					
		}else {
			throw new ActionNotFoundException($moduleName, $actionName);
		}
		

		return $action;
		
	}
	
	public static function resolveActionPath($moduleName, $actionName){
		$actionObject = self::resolveActionObjectName($moduleName, $actionName);
		$path = MODULE_ROOT . "/$moduleName/Actions/$actionObject.php"; 
		return $path;
	}
	
	private static function resolveActionObjectName($moduleName, $actionName){
		return ucfirst($moduleName) . ucfirst($actionName);
	}
	
}

?>