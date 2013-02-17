<?php
pminclude('sfp:smarty.PhorgedSmarty');
pminclude('phorge:core.SimpleExceptionDispatcher');


class SmartyExceptionDispatcher extends SimpleExceptionDispatcher  {
	    
    
    protected function resolveViewPath($view){                    
		#return $this->viewDirectory . "/$view" . $this->appendValue;
        return $view . parent::getAppendValue();
	}
    
    protected function viewExists($view){		
		#return file_exists($this->resolveViewPath($view));
        $path = parent::getViewDirectory() .'/' . $this->resolveViewPath($view);        
        return file_exists($path);
		
	}
    
	protected function viewException(Exception $e, $viewPath, $request){
        
		$response = Phorge::getResponse();
				
		$exceptionName = get_class($e);
		#$actionName = $request->get(ACTION);
		#$queryVars = $_GET;
		//unset($queryVars[SUB_ACTION_KEY]);
		
		#unset($queryVars[ACTION]);
		#unset($queryVars[SUB_ACTION]);
		#$response->put('query_vars', $queryVars);
		

		
		$message = $e->getMessage();
		$response->put('redirect', UrlService::getCurrentUrl());

		$response->put('exception_name', $exceptionName);
		$response->put('message', $message);
		$response->put('exception', $e);
        $response->put('e', $e);
		$response->combine($request);
		
		//require $viewPath;
		
		


		
		//$view = $response->get(VIEW_KEY);
		
		#$template = Phorge::getConfigProperty('smarty.template');

		$smarty = new PhorgedSmarty();
		
		
		foreach($request as $key => $var){
			$smarty->assign($key, $var);
		}
		
		foreach($response as $key => $value){
			//print ("$key:$value<br>");
			$smarty->assign($key, $value);
		}
		
		
		$result = $smarty->fetch($viewPath);

		return $result;
		
		
	}
	
}

?>