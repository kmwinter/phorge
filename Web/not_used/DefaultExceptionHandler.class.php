<?php
class DefaultExceptionHandler implements ExceptionHandler {
	
	


	public static function displayException(Exception $e, Request $request){
		$handler = new DefaultExceptionHandler();
		$handler->display($e, $request);

		
	}
	
	public function display($e, Request $request){
		
		
		$view = $this->resolveView($e);
		if($view === false){
			trigger_error($exceptionName . ": $message");	
		}
		
		$viewPath = $this->resolveViewPath($view);
		$this->viewException($e, $viewPath, $request);
	}
	
	public function resolveView(Exception $e){
		
		//see if exception specific view exists
		$view = get_class($e);
		if($this->viewExists($view)){			
		
			return $view;
			
		}		
		
		//see if default view exists
		$view = DEFAULT_VALUE;
		
		if($this->viewExists($view)){		    		
			return $view;
		}

		//return false because no view could be found
		return false;
		
	}
	
	
	public function resolveViewPath($view){
		return FRAMEWORK_ROOT . "/Exceptions/Views/$view.php";
	}
	
	
	
	public function viewExists($view){
		
		return file_exists($this->resolveViewPath($view));
		
	}
	
	protected function viewException(Exception $e, $viewPath, $request){
		global $model;
				
		$model = new Model;
				
		$exceptionName = get_class($e);
		
		$queryVars = $_GET;
		unset($queryVars['action']);
		unset($queryVars['module']);
		$model->put('queryVars', $queryVars);
		
		
		
		$message = $e->getMessage();
		$model->put('exception_name', $exceptionName);
		$model->put('message', $message);
		$model->put('exception', $e);
		$model->combine($request);
		
		require $viewPath;
		//returning false because exception occurred
		return false;
		
	}
	
}

?>

