<?php
pminclude('phorge:core.interfaces.Dispatcher');
interface BlockDispatcher extends Dispatcher{
	
	public function getModelAndView(Request $request, Response $response, $blockName);
    public function setNamespace($namespace);
    public function getNamespace();

	
	
}

?>