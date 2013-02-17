<?php

class DefaultViewer implements Viewer  {
	

	
	public function showView($viewPath, Model $model, Request $request){
		if(file_exists($viewPath)){
			require $viewPath;
		}else {
			throw new ViewNotFoundException($viewPath, $model->get(MODULE_KEY));
		}
		
	
	}
	
	public function showBlock($viewPath, Model $model, Request $request){
		if(file_exists($viewPath)){
			require $viewPath;
		}else {
			throw new ViewNotFoundException($viewPath, $model->get(MODULE_KEY));
		}
		
	}
	
	
	public function showException(Exception $e){
		
		global $log;
		global $model;
		global $request;
		
		$model = new Model;
				
		$exceptionName = get_class($e);
		
		
		
		$message = $e->getMessage();
		$model->put('exception_name', $exceptionName);
		$model->put('message', $message);
		$model->put('exception', $e);
		$model->combine($request);

		$className = EXCEPTION_RESOLVER;

		$resolver = new $className;
		$viewPath = $resolver->resolveExceptionViewPath($e);
		
		
		if(file_exists($viewPath)){			
			require($viewPath);
			
			return true;
		}
		
		$defaultPath = $resolver->resolveDefaultExceptionViewPath($e);
		
		
		if(file_exists($defaultPath)){
		    
			require($defaultPath);
			return true;
		}

		
		trigger_error($exceptionName . ": $message");
		
	}

}



?>