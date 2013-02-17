<?php

pminclude('phorge:core.interfaces.ExceptionDispatcher');
class SimpleExceptionDispatcher implements ExceptionDispatcher  {
	

    public function handleException(Exception $e, Request $request, ViewDispatcher $dispatcher){ 
        
	Logger::error($e->getMessage());
		
	try {
            
            $view = $this->resolveView($e, $dispatcher);
            

            /*
            if($view === false){
                throw new ViewNotFoundException(get_class($e));
            }
            */
            $response = Phorge::getResponse();
            $exceptionName = get_class($e);
		
            #$queryVars = $_GET;
            #unset($queryVars['action']);
            #unset($queryVars['module']);
            #$response->put('queryVars', $queryVars);



            $message = $e->getMessage();
            $response->put('exception_name', $exceptionName);
            $response->put('message', $message);
            $response->put('exception', $e);
            $response->combine($request);

            Phorge::getInstance()->setResponse($response);
            $modelAndView = new ModelAndView($response, $view);

            //$viewPath = $this->resolveViewPath($view);        
            //$result =  $this->viewException($e, $viewPath, $request);

            #$result = $dispatcher->getViewOutput($modelAndView);
            #return $result;
            return $modelAndView;

        }catch(Exception $ex){
            Logger::error("Error encountered while handling exception: " . $ex->getMessage());        
            
            echo "An error was encounted while handling an exception: " . $ex->getMessage();

            if(Phorge::getConfigProperty(DEBUG) === 'true'){
                foreach($ex->getTrace() as $step){
                    echo '<div class="trace-item-detail">';
                    echo $step['file'] .': ' . $step['class'] .  $step['type'] . $step['function'] . ' <b>line ' .
                                            $step['line'] . '</b></b>';
                    echo '</div>';
                }
                die;
            }else {
                trigger_error("An exception was encountered and couldn't be handled gracefully: {$e->getMessage()}", E_USER_ERROR);
            }
            

        }
		
		
    }
	

    public function configure(Module $module){
      
    }
    
	/*public static function displayException(Exception $e, Request $request){
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
	}*/
	
	private function resolveView(Exception $e, ViewDispatcher $dispatcher){
		
		
        
        try {
            //see if exception specific view exists
		
            $view = get_class($e);
            $path = $dispatcher->resolveViewPath($view);
		
			return $view;
			
		}catch(ViewNotFoundException $e){		
		
            //see if default view exists
            $view = Phorge::getConfigProperty(DEFAULT_ERROR_VIEW);		
            $path = $dispatcher->resolveViewPath($view);
			return $view;
            
        }

	}
	
	
	protected function resolveViewPath($view){                    
		return $this->viewDirectory . "/$view" . $this->appendValue;
	}
	
	
	
	protected function viewExists($view){
		
		return file_exists($this->resolveViewPath($view));
		
	}
	
	protected  function viewException(Exception $e, $viewPath, $request){
		//global $response;
				
		//$response = new Response($e);
		$response = Phorge::getResponse();		
		$exceptionName = get_class($e);
		
		$queryVars = $_GET;
		unset($queryVars['action']);
		unset($queryVars['module']);
		$response->put('queryVars', $queryVars);
		
		
		
		$message = $e->getMessage();
		$response->put('exception_name', $exceptionName);
		$response->put('message', $message);
		$response->put('exception', $e);
		$response->combine($request);
		
		Phorge::getInstance()->setResponse($response);
		
        return Phorge::getIncludeFileContent($viewPath);
	}


    public function diagnostic($prefix = ''){
        return '';
    }
	
}

?>