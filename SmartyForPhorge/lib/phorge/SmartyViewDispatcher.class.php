<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SmartyViewDispatcher.class
 *
 * @author kwinters
 */
pminclude('phorge:core.SimpleViewDispatcher');
pminclude('phorge:exceptions.ViewNotFoundException');
pminclude('sfp:smarty.PhorgedSmarty');
class SmartyViewDispatcher extends SimpleViewDispatcher {

    
    public function getViewOutput(ModelAndView $modelAndView){
        
        //$this->modelAndView = $modelAndView;            
        $view = $modelAndView->getView();
        $viewPath = $this->resolveViewPath($view);
        #$viewPath = $this->resolveViewPath($modelAndView);            
        return $this->showView($viewPath, $modelAndView->getResponse());
        
        
    }
    
    /*
     protected function resolveViewPath(ModelAndView $modelAndView){
        $view = $modelAndView->getView() . parent::getAppendValue();
        $path =  parent::getViewDirectory() . "/" . $view ;
        if(! file_exists($path)){
            throw new ViewNotFoundException($path);
        }

        return $view;
    }
    */

    /*
    protected function resolveViewPath($viewName){
        if(empty($this->viewDirectory)){
            throw new Exception("viewDirectory not defined");
        }

        $path = rtrim($this->viewDirectory, '/') . "/$viewName";

        if(! file_exists($path)){
            throw new ViewNotFoundException($path);
        }

        return $path;

    }
    */

 
    private function showView($viewPath, $response){
		
				
        $request = Phorge::getRequest();
        #$response = Phorge::getResponse();

		$smarty = new PhorgedSmarty();
		
		foreach($request as $key => $var){					
			$smarty->assign($key, $var);
		}
		
		
		foreach($response->getAll() as $key => $value){
						
			$smarty->assign($key, $value);
		}
		
		$output = $smarty->fetch($viewPath);
		return $output;
		
	}
    
    


     
}
?>
