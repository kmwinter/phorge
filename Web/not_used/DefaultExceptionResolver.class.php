<?php
class DefaultExceptionResolver implements ExceptionResolver {
	

	public static function resolveExceptionViewPath(Exception $e){
		$exceptionName = get_class($e);
		return FRAMEWORK_ROOT . "/Exceptions/Views/$exceptionName.php";
	}
	
	public static function resolveDefaultExceptionViewPath(){
		return FRAMEWORK_ROOT . "/Exceptions/Views/Default.php";
	}
	
}