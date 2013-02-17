<?php
pminclude('phorge:core.interfaces.Block');
pminclude('phorge:core.Request');
pminclude('phorge:core.Response');
pminclude('phorge:core.ModelAndView');
abstract class Block {
	
	protected $blockName;
	//protected $module;
	
	
	public function __construct($blockName){
		$this->blockName = $blockName;
		//$this->module = $module;				
	}
	
	
	
	public function process(Request $request, Response $response){
		$returned = $this->generateResponse($request, $response);
		
		if($returned instanceof Response){
			$dispatcher = DispatcherFactory::getDispatcher(BLOCK);
			$view = $dispatcher->resolveView($this->blockName, $this->module);			
			return new ModelAndView($returned, $view);
		}
		
		if($returned instanceof ModelAndView){
			return $returned;
		}
		
		throw new GeneralException('Invalid return type found for ' . get_class($this));
	
	}
	
	protected function generateResponse(Request $request, Response $response){
		return $response;
	}
	
	protected function findDefaultView($result, $actionName, $moduleName = null){
		$dispatcher = DispatcherFactory::getDispatcher(BLOCK);
		return $dispatcher->resolveView($result, $actionName, $moduleName);
	}
	
	
	public function getResult(){
		return SUCCESS;
	}
	
	public function getBlockName(){
		return $this->blockName;
	}
	
	
	public function getModule(){
		return $this->module;
	}
	
	
}


?>