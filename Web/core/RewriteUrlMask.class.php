<?php

pminclude('phorge:core.defaults.DefaultUrlMask');

class RewriteUrlMask extends DefaultUrlMask {

    private $actionExtension;

    public function getActionExtension() {
        return $this->actionExtension;
    }

    public function setActionExtension($actionExtension) {
        $this->actionExtension = $actionExtension;
    }



	public function parse(Request $request){
		

        if(!$this->actionExtension){
            $this->actionExtension = Phorge::getConfigProperty(DEFAULT_ACTION_EXTENSION);
        }

        
		$base = Phorge::getConfigProperty(WEB_ROOT);			
		$uri = str_replace($base, '', $_SERVER['REQUEST_URI']);
		
        if(strstr($uri, '.php')){
            throw new Exception("$uri is not a valid rewritable url");
        }
		
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
				
				if(strstr($part, $this->actionExtension)){
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
				
		unset($parts[$qspos]);
						
		if(count($parts) == 0){
			//is approot
			Logger::debug("This is the application root");			
			return true;												
		}
		
		##determine module
		if($actionPos != 0){
			#$module = $parts[$actionPos - 1];				
            $modTest = $parts[0];
            $moduleConfig = Phorge::getModuleConfig();
            $moduleList = $moduleConfig->getModuleList();
            if(in_array($modTest, $moduleList)){
                $module = $modTest;
            }else {
                $module = null;
            }                        
		}


        ## determine Action
		if($actionPos != -1){
            
            ## action identified in URL, 
            $startPos = $module == null ? 0 : 1 ;                      
            
            #action path is composed of everything after module and before action element (determined by extension)
            for($i = $startPos; $i <= $actionPos; $i++ ){
                $actionStr .= $parts[$i] . '/';
            }
            $action = str_replace($this->actionExtension, '', rtrim($actionStr, '/'));
			#$action = str_replace($this->actionExtension, '', $parts[$actionPos]);
            
            
            ## look for ID		
            ## id is located directly after action element of URL  and before query string
            if(($qspos > ($actionPos + 1)) || ( (count($parts) -1)  > $actionPos && $qspos == -1)  ){
                $id = $parts[$actionPos + 1];
                $this->id = $id;

                $request->put(ID, $id);
            }
		}else {
            if($module == null){
                //no action and no module is defined
                throw new Exception("Invalid URL: No module and no action defined");
            }
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
			$url .= "/$action" . $this->actionExtension;
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