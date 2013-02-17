<?php
pminclude('phorge:core.interfaces.Dispatcher');
interface ExceptionDispatcher extends Dispatcher{
	
 

	public function handleException(Exception $e, Request $request, ViewDispatcher $dispatcher);
	
}


?>