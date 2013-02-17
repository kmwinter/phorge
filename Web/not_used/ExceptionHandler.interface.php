<?php
interface ExceptionHandler {
	
 

	public static function displayException(Exception $e, Request $request);
	public function display($e, Request $request);
	//public function resolvePath(Exception $e);
	
	public function resolveView(Exception $e);
	
	public function resolveViewPath($view);
	
	public function viewExists($view);
	
}


?>