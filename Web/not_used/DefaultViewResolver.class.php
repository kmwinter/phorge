<?php
class DefaultViewResolver implements ViewResolver  {
	
	//private $view;

	
	/*public  function resolveBlock($blockName, $moduleName = null){
		
		if(! $this->blockViewExists($blockName, $moduleName)){
			throw new ViewNotFoundException($blockName, $moduleName);
		}
		
		$this->view = $blockName;
		return $this->view; 
		
	}*/
	
	public  function resolve($moduleName, $actionName, $result){
		$view = null;
		//$actionName = $action->getblockName();
		//$moduleName = $module->getModuleName();

		
		if($result == SUCCESS){
			
			
			if($this->viewExists($actionName, $moduleName)){
				//$this->view = $actionName;
				//return $this->view;

				return $actionName;
			
			}
			
			$combo = $actionName . DEFAULT_SUCCESS_VIEW;
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
	
	
	
	public function resolveBlock($blockName, $moduleName = null){
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
	
	/*public  function actionViewExists($viewName, $moduleName){
		
		return file_exists(MODULE_ROOT . "/$moduleName/Views/$viewName.php");
	}
		
	
	*/
	
	
	
}


?>