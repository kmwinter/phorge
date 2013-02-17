<?php
interface ExceptionResolver {
	
	//public function resolve(Exception $e);
	
	public static function resolveExceptionViewPath(Exception $e);
	public static function resolveDefaultExceptionViewPath();
	
}