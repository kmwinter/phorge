<?php

interface ActionResolver { 

	public function resolve($moduleName, $actionName, Request $request);
	
	public static function resolveActionPath($moduleName, $actionName);
}


?>