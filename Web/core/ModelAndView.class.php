<?php
class ModelAndView {
	private $response;
	private $view;
        
	

	public function __construct(HashMap $response, $view){
		$this->response = $response;
		$this->view = $view;			
		
	}




	public function getResponse(){
		return $this->response;
	}
	
	public function setResponse(Response $response){
		$this->response = $response;
	}
	
	public function getView(){
		return $this->view;
	}
	
	public function setView($view){
		$this->view = $view;
	}
	
	

}

?>