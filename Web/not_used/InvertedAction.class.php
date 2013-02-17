<?php

class InvertedAction extends Action {
	
	public function __construct(Request $request, $actionName){
		$this->POSITIVE_RESPONSE = FAILURE;
		$this->NEGATIVE_RESPONSE = SUCCESS;
		parent::__construct($request, $actionName);
		
	}
	
	
}

?>