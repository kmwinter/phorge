<?php
interface Dispatcher {
	
	//public function getModelAndView(Request $request, Response $response, $elementName);
    public function configure(Module $module);
    public function diagnostic($prefix = '');
}

?>