<?php
pminclude('phorge:core.UrlService');

class Url {
	
	private $path;	
	protected $params;
	protected $anchor;
	
	
	public function __construct($path, $params = array(), $anchor = null){
		
		$this->path = $path;

		if(is_array($params)){
			$this->params = $params;
		}else {
			Logger::warn("invalid params argument passed into URL $path");
			$this->params = array();
		}
		
		$this->anchor = $anchor;
	}
	
	public function setPath($path){
		$this->path = $path;
	}
	
	public function getPath(){
		return $this->path;
	}
	
		
	public function getAnchor(){
		return $this->anchor;
	}
	
	public function setAnchor($anchor){
		$this->anchor = $anchor;
	}
	

	
	
	public function setParams($params){
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
	}
	
	
	public function __toString(){
					
		return UrlService::getUrlString($this);
		
		
	}
	
	
	
}


?>