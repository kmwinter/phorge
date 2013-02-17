<?php
pminclude('phorge:core.UrlService');
pminclude('phorge:core.Url');

class ActionUrl extends URL {
	
	protected $path;
	protected $module;
	protected $action;
	protected $id;
	protected $params;
	protected $anchor;
	
	
	public function __construct($action, $module = null, $id = null, $params = array(), $anchor = null){
		
		$this->action = $action;
		$this->module = $module;
		$this->id = $id;
		$this->params = $params;
		$this->anchor = $anchor;
	}
	
	public function setAction($action){
		$this->path = $action;
	}
	
	public function getAction(){
		return $this->action;
	}
	
	public function setModule($module){
		$this->module = $module;
	}
	
	public function getModule(){
		return $this->module;
	}

	
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	/*public function getAnchor(){
		return $this->anchor;
	}
	
	public function setAnchor($anchor){
		$this->anchor = $anchor;
	}*/
	

	
	
	/*public function setParams($params){
		if(is_array($params)){
			$this->params = $params;
			return true;
		}
		
		return false;
	}
	
	public function getParams(){
		return $this->params;
	}
	
	public function addParam($key, $value, $override = true){
		if(!key_exists($key, $this->params) || $override){
			$this->params[$key] = $value;
		}
	}*/
	
	/*public function getUrlString(){
		try {
			$mask = pminclude(Framework::getConfigProperty(URL_MASK));		
			$obj = new $mask;
			
			$tmp = $obj->getUrlString($this->module, $this->action, $this->params, $this->subaction);
			
			return $tmp;
		}catch (PackageManagerException $e){
			
			return "Error occured fetching URL: " . $e->getMessage();
		}
	}*/
	
	public function __toString(){
		//public static function getUrl($location, $action = null, $properties = array(), $subAction = null){
				
		return UrlService::getActionUrlString($this);
		
		
	}
	
	
	
}


?>