<?php

pminclude('phorge:core.interfaces.UrlMask');
class DefaultUrlMask implements UrlMask {
	
	
	protected $action;
	protected $module;
	
	public function __construct(){
		## determine application root. 
		$parts = split('/', $_SERVER['SCRIPT_NAME']);
		array_pop($parts);
		$appRoot = implode($parts);
		Framework::setConfigProperty(WEB_ROOT, "/$appRoot/");
	}
	
	
	# deconstruct url path into basic components
	public function parse(Request $request){
		
		$this->action = $request->get(ACTION);							
		$this->module = $request->get(MODULE);		
		
		
	}
	
	
	public function getUrlString($path, $properties = array(), $anchor = null){
		
		if(substr($path, 0, 1) == '/'){
			$url = $path;
		}else {
	
			$base = rtrim(Framework::getConfigProperty(WEB_ROOT), '/');
			$url = "$base/$path";
			
		}
		
		if(!is_array($properties)){
			$properties = array();
		}
		
		
		foreach($properties as $key => $value){
			if(empty($key)){
				continue;
			}
			if(strstr($url, '?')){
				$url .= '&';
			}else {
				$url .= '?';
			}
			$url .= "$key=$value";
		}

		if($anchor){
			ltrim($anchor, '#');		
			$url .= "#$anchor";
		}
				
		return $url;
		
		
		
	}
	
	
	public function getActionUrlString($action, $module = null, $id = null, $properties = array(), $anchor = null){
		
		if(!is_array($properties)){
			$properties = array();
		}
		
		unset($properties[ACTION]);
		unset($properties[MODULE]);
		
				
		$url = $_SERVER['SCRIPT_NAME'];		
		$properties[ACTION] = $action;		
		$properties[MODULE] = $module;			
		$properties[ID] = $id;
		
		foreach($properties as $key => $value){
			if(empty($key)){
				continue;
			}
			if(empty($value)){
				continue;
			}
			if(strstr($url, '?')){
				$url .= '&';
			}else {
				$url .= '?';
			}
			$url .= "$key=$value";
		}


		if($anchor){
			ltrim($anchor, '#');		
			$url .= "#$anchor";
		}
		
		return $url;
		
		
		
	}
	
	
	public function getModule(){
		return $this->module;
	}
	public function getAction(){
		return $this->action;		
	}
	
	
}


?>