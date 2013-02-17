<?php

pminclude('phorge:core.defaults.DefaultUrlMask');

class MappedRewriteUrlMask extends DefaultUrlMask {
	
	
    private $actionExtension;
    private $mappings;


    public function getActionExtension() {
        return $this->actionExtension;
    }

    public function setActionExtension($actionExtension) {
        $this->actionExtension = $actionExtension;
    }

    public function getMappings() {
        return $this->mappings;
    }

    public function setMappings($mappings) {
        $this->mappings = $mappings;
    }



	public function parse(Request $request){
		
		$base = Phorge::getConfigProperty(WEB_ROOT);			
		$uri = str_replace($base, '', $_SERVER['REQUEST_URI']);
		
		
		if($request->get('REQUEST_METHOD') == 'GET'){
			//normalize GET url
			$uri = str_replace(strstr($uri, '?'), '', $uri) . '/' . $request->get('QUERY_STRING');			
		}
		
		
		
				
		$origs = split('/', $uri );
				
		$module = null;
		$action = null;
		
		$parts = array();
		$querystring = array();
		$qspos = -1;
		$actionPos = -1;		
		
		
		
		#parse uri into it's segments. Also populate request
		$index = 0;
		foreach($origs as $place => $part){
				
			if($part != null){
				$parts[$index] = $part; 
				
				if(strstr($part, '.do')){
					$actionPos = $index;
				}
				
				if(strstr($part, '=')){
					#these are the GET params, populate request
					$qspos = $index;
					$pairs = split('&', $part);				
					foreach($pairs as $pair){
						list($key, $var) = split('=', $pair);
						$querystring[$key] = $var;
						$request->put($key, $var);				
					}									
					
				}	
				$index++;	
				
			}
		}
		
				
		
		
		
						
		if(count($parts) == 0){
			//is approot
			Logger::debug("This is the application root");			
			return true;												
		}
		
		if($actionPos == -1){
			if(count($parts) == 1){
				## is module root
				$module = $parts[0];
			}
		}else {
			$action = str_replace('.do', '', $parts[$actionPos]);
		}
		
		
		if($actionPos > 0){
			$module = $parts[$actionPos - 1];				
		}
		
		## look for ID		
		if(($qspos > ($actionPos + 1)) || ( (count($parts) -1)  > $actionPos && $qspos == -1)  ){
			$id = $parts[$actionPos + 1];
			$this->id = $id;		
				
			$request->put(ID, $id);			
		}
		
		
	
		$this->action = $action;		
		$this->module = $module;
		
		$request->put(ACTION, $action);
		$request->put(MODULE, $module);
		
	}
	
	
	public function getActionUrlString($action, $module = null, $id = null, $properties = array(), $anchor = null){
				
		$url = rtrim(Phorge::getConfigProperty(WEB_ROOT), '/');	
		
		$mkey = MODULE;
		$akey = ACTION;
		$sakey = SUB_ACTION;		
		
		
		if(!is_array($properties)){
			$properties = array();
		}
		
		
		unset($properties[ACTION]);		
		unset($properties[ID]);
		unset($properties[MODULE]);
		unset($properties['repost']);
		
		
		if($module){
			$url .= '/' . ltrim($module, '/');
		}
				
		if($action){											
			$url .= "/$action.do";
		}
		
		
		if($id){
			$url .= "/$id";
			unset($properties[ID]);
		}
		
		$hasFirstPair = false;
		
			
		
		foreach($properties as $key => $value){
			if(empty($value)){
				continue;
			}			
			if(!$hasFirstPair){
				$url .= '/';
				$hasFirstPair = true;
			}else {
				$url .= '&';
			}
			
						
			$url .= "$key=$value";
		}

		if($anchor){
			ltrim($anchor, '#');
			$url.=$anchor;
		}
		
		
		return $url;
	
	}
	
}


?>