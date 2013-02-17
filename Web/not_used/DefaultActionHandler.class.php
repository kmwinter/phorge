<?php

class DefaultActionHandler {
	
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
	
	public static function resolveActionObjectName($moduleName, $actionName){
		return ucfirst($moduleName) . ucfirst($actionName);
	}
	
	
	
	
	
	/*====================================================================
	View Methods
	=====================================================================*/
	
	public  function resolveView($moduleName, $actionName, $result){
		$view = null;
		//$actionName = $action->getblockName();
		//$moduleName = $module->getModuleName();

		
		if($result == SUCCESS){
			
			
			if($this->viewExists($actionName, $moduleName)){
				//$this->view = $actionName;
				//return $this->view;

				return $actionName;
			
			}
			
			$combo = $actionName . 'Result';
			//look the default action view 
			
			
			
			//look for a success view specific to this action	
			if($this->viewExists($combo, $moduleName)){				
				//$this->view = $combo;	
				//return $this->view;
				return $combo;
			
			}
			
			//look for a general success view	
			if($this->viewExists(DEFAULT_SUCCESS_VIEW, $moduleName)){
				//$this->view = DEFAULT_SUCCESS_VIEW;
				//return $this->view;
				return DEFAULT_SUCCESS_VIEW;
			}
			
			//look for a template success view
			

			//no adequate view found
			throw new ViewNotFoundException($actionName, $moduleName);
			
			
		}else {
			
			$combo = $actionName . DEFAULT_FAILURE_VIEW;
			$formCombo = $actionName . 'Form';
			//look for a failure view specfic to this action
			if($this->viewExists($combo, $moduleName)){
				//$this->view = $combo;
				//return $this->view;
				return $combo;
			
			}
			//look for a 'Action'Form view	
			if($this->viewExists($formCombo, $moduleName)){
				//$this->view = $formCombo;
				//return $this->view;
				return $formCombo;
			
			}
			
			//look for a general Failure view	
			if($this->viewExists(DEFAULT_FAILURE_VIEW, $moduleName)){
				//$this->view = DEFAULT_FAILURE_VIEW;
				//return $this->view;
				return DEFAULT_FAILURE_VIEW;
			
			}		
			//no adequate view found.
			throw new ViewNotFoundException($actionName, $moduleName);
			
		}
		 
		
	}	
	
	
	public  function viewExists($viewName, $moduleName){
		
		if($moduleName){
			return file_exists(MODULE_ROOT . "/$moduleName/Views/$viewName.php");
		}else {
			return file_exists(BLOCK_ROOT . "/Views/$viewName.php");
		}
	}
	
	
	public  function  resolveViewPath($viewName, $moduleName){
		if($moduleName){
			return MODULE_ROOT . "/$moduleName/Views/$viewName.php";
		}else {
			return BLOCK_ROOT . "/Views/$viewName.php";
		}
	}
	
	
	public function showView($viewPath, Model $model, Request $request){
		
		if(file_exists($viewPath)){
			require $viewPath;
			return true;
		}else {
			throw new ViewNotFoundException($viewPath, $model->get(MODULE_KEY));
		}
		
	
	}
	
	
	
	
	/*public function resolveBlock($blockName, $moduleName = null){
		$viewName = $blockName;
		if(! $this->blockViewExists($viewName, $moduleName)){
			throw new ViewNotFoundException($blockName, $moduleName);
		}
		
		return $viewName;
	}
	
	public function resolveBlockViewPath($viewName, $moduleName = null){
		if($moduleName){
			return MODULE_ROOT . "/Blocks/Views/$viewName.php";	
		}else {
			return BLOCK_ROOT . "/Views/$viewName.php";	
		}		
	}
	
	public function blockViewExists($viewName, $moduleName = null){
		if($moduleName){
			return file_exists(MODULE_ROOT . "/Blocks/Views/$viewName.php");	
		}else {
			return file_exists(BLOCK_ROOT . "/Views/$viewName.php");	
		}
	}
	
	
	public function showBlock($viewPath, Model $model, Request $request){
		if(file_exists($viewPath)){
			require $viewPath;
		}else {
			throw new ViewNotFoundException($viewPath, $model->get(MODULE_KEY));
		}
		
	}
	
	*/
	
	
	
}


?>