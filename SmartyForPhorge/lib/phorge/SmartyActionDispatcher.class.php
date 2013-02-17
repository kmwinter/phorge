<?php
pminclude('sfp:smarty.PhorgedSmarty');
pminclude('phorge:core.SimpleActionDispatcher');
class SmartyActionDispatcher extends SimpleActionDispatcher  {
	
	
	
	public  function resolveViewPath(View $view){		
		
		$moduleName = $view->getModule();
		$viewName = $view->getName();
	
		if($moduleName){		
			return  "Modules/$moduleName/$viewName.tpl";
		}
		
		
		return "Views/$viewName.tpl";
	}
	
	
	
	protected  function viewExists($viewName, $moduleName = null){
		
		global $template;
		if($moduleName){
			return file_exists(Phorge::getConfigProperty('smarty.template.dir') . "/$template/Modules/$moduleName/$viewName.tpl");
		}		
		return file_exists(Phorge::getConfigProperty('smarty.template.dir') ."/$template/Views/$viewName.tpl");
		
	}
	
	
	public function showView(View $view, Response $response, Request $request){

		#$moduleName = $response->get(MODULE);
		#$actionName = $response->get(ACTION);		
		
		$viewPath = $this->resolveViewPath($view);
	
		$smarty = new PhorgedSmarty();
		
		foreach($request as $key => $var){					
			$smarty->assign($key, $var);
		}
		
		
		foreach($response->getAll() as $key => $value){
						
			$smarty->assign($key, $value);
		}
		
		return $smarty->fetch($viewPath);
		
		
	}
	
	
	
	
	
	
	
}


?>