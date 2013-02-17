<?php

interface Viewer {
	
	public function showView($viewPath, Model $model, Request $request);
	public function showBlock($viewPath, Model $model, Request $request);
	public function showException(Exception $e);
	
}




?>