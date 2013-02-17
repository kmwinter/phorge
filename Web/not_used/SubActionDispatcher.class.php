<?php
pminclude('phorge:core.ActionDispatcher');

class SubActionDispatcher extends ActionDispatcher{
	
	public function process($actionName, $moduleName = null){

		
		
		if($moduleName){
			$package = "modules:$moduleName.Actions." . $moduleName . $actionName;			
		}else {
			$package = "actions:$actionName";
		}
		
		
		try {
			$className = pminclude($package);
			 
			$action = new $className($actionName, $moduleName);
			
			return $action;
			/*$view = $action->process();
			
			return $view;*/
			
		}catch(PackageManagerException $pme) {
			
			throw new ActionNotFoundException($actionName, $moduleName);
		}
				
		
		
		

	}
	
}




?>


