<?php
class DefaultBlockHandler implements BlockHandler {
	
	protected $blockName;
	protected $moduleName;
	
	/*public static function displayBlock($blockName, $moduleName = null){
		
		//$handler = BlockHandlerFactory::getHandler();
		$handler = new DefaultBlockHandler();
		$handler->display($blockName, $moduleName);
	}
	
	public function display($blockName, $moduleName = null){
		
		
	}*/
	
	public function resolve(Request $request, $blockName, $moduleName = null){
		
			
		if(! file_exists($this->resolveBlockPath($blockName, $moduleName))){
			throw new BlockNotFoundException($blockName, $moduleName);
		}
		
		require ($this->resolveBlockPath($blockName, $moduleName));
		
		$block = new $blockName($request, $blockName);
				
		return $block;
	}
	
	public function resolveBlockPath($blockName, $moduleName = null){
		if($moduleName){
			return MODULE_ROOT . "/$moduleName/Blocks/$blockName.php";
		}
		
		return BLOCK_ROOT . "/$blockName.php";
	}
	
	
	public function resolveView($blockName, $moduleName = null){
		if(! $this->viewExists($blockName, $moduleName)){
			throw new ViewNotFoundException($blockName, $moduleName);
		}
	}
	
	public function resolveViewPath($blockName, $moduleName = null){
		if($moduleName){
			return MODULE_ROOT . "/$moduleName/Blocks/Views/$blockName.php";
		}
		
		return BLOCK_ROOT . "/Views/$blockName.php";
	}
	
	public function viewExists($blockName, $moduleName = null){	

		if($moduleName){
			return file_exists(MODULE_ROOT . "/$moduleName/Blocks/Views/$blockName.php");
		}
		
		return file_exists(BLOCK_ROOT . "/Views/$blockName.php");
		
	}
	
	
	
	public function showView($viewPath, Model $model, Request $request){

		require $viewPath;
		return true;
		
	}
	
	
}


?>