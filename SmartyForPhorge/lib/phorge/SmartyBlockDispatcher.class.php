<?php
pminclude('sfp:smarty.PhorgedSmarty');
pminclude('phorge:core.BlockDispatcher');
class SmartyBlockDispatcher extends BlockDispatcher  {
	
	
	
	/*public static function displayBlock($blockName, $moduleName = null){
		$handler = new SmartyBlockHandler();
		$handler->display($blockName, $moduleName);
	}*/
	
		
	public function resolveViewPath(View $view){
	
		
		$moduleName = $view->getModule();
		$viewName = $view->getName();
	
		if($moduleName){
			return "Modules/$moduleName/$viewName.tpl";
		}
		return "Views/$viewName.tpl";
	
	}
	
	public function viewExists($viewName, $moduleName = null){
		global $template;
		if($moduleName){
			$path = Phorge::getConfigProperty('smarty.template.dir') . "/$template/Modules/$moduleName/$viewName.tpl";			
			return file_exists($path);
		}
		$path = Phorge::getConfigProperty('smarty.template.dir') . "/$template/Views/$viewName.tpl";		
		return file_exists($path);
		
	}
	
	public function showView(View $view, Response $response, Request $request){
	
		global $template; 
	
		$viewPath = $this->resolveViewPath($view);
		
		
				
		//$response = $blockresponse->getresponse($this->blockName, $this->moduleName);

		
		if(! $response instanceof Response){
			throw new GeneralException("Missing Response for block $viewPath");
		}
		
		#$smarty = new CustomSmarty($template, FORCE_COMILE);
		$smarty = new PhorgedSmarty();
		
		
		foreach($request as $key => $var){
			$smarty->assign($key, $var);
		}
		
		foreach($response as $key => $value){
			//print ("$key:$value<br>");
			$smarty->assign($key, $value);
		}
		
		
		//$smarty->display($viewPath);
		
		//$smarty->display($viewPath);
		$fetched  =  $smarty->fetch($viewPath);
		
		return $fetched;
		
		
	}
	
}
?>