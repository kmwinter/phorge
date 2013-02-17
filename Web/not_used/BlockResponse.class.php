<?php
#pminclude('phorge:core.HashObject');
pminclude('phorge:exceptions.GeneralException');
class BlockResponse extends HashMap {
	
	
	/*public function __construct(Request $request){
		$this->combine($request);
		
	}*/
	
	public function putResponse(Response $response, $blockName, $moduleName = null){		
		$this->put($this->getKey($blockName, $moduleName), $response, true);
	}
	
	public function getResponse($blockName, $moduleName = null){
		$key = $this->getKey($blockName, $moduleName);
		
		if(!$this->containsKey($key, true)){
			throw new GeneralException("No Response found for block [$key]");
		}
		
		return parent::get($key);
	}
	
	
	
	private function getKey($blockName, $moduleName = null){
		$key = $blockName;
		if($moduleName){
			$key = "$moduleName-$blockName";	
		}
		
		return $key;
	}
	
}

?>