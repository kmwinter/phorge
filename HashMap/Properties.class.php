<?php

/**
 * @author Kirk Winters, kirk.winters@gmail.com
 * 
 * 
 * An extended HashMap that has additional functionality for storing self-referncing properties.
 * references to other properties keys can be made with the java ${keyname} syntax. 
 * 
 * For example
 * $properties->put('foo', 'bar');
 * $properties->put('myfoo', ${foo});
 * 
 * echo $properties->get('myfoo');
 * 
 * will output 'bar'
 * 
 * this can be very handy for building application paths. example:
 * 
 * $properties->put('application.root', '/www/apps/myapp');
 * $properties->put('log.directory', '${application.root}/logs');
 * 
 * this will result in the log.directory property being /www/apps/mayapp/logs
 * 
 * 
 */
class Properties extends HashMap {

    public function put($key, $value){

        if(is_scalar($value)){
            $value = $this->searchAndReplace($key, $value);
        }
        
        parent::put($key, $value);
    }

	/**
	 * Loads properties from a given properties file path
	 * 	 
	 * @param String $path
	 */
	public function loadProperties($path){
		$this->load(file_get_contents($path));	
	}
	
	/**
	 * Loads properties from a string 
	 * 	 
	 * @param String $input
	 */
	public function load($input) {
		$lines = split("\n", $input);
		foreach($lines as $line){
			$line = trim($line);
			
			if($line == ''){
				#is empty line
				continue;
			}
					
			if(strpos($line, '#') === 0){
				#is comment				
				continue;
			}
			
			if(strpos($line, '=') === false){
				#missing '=' 
				continue;
			}
			
			list($key, $value) = split('=', $line);
			$key = trim($key);
			$value = trim($value);
			
			#do jsp-like replacements
			
			$value = $this->searchAndReplace($key, $value);
			
			#preg_match_all('/\${([a-zA-Z0-9._-]+)}*/', $value, $matches );
			#foreach($matches[1] as $match){			
			#	$value = str_replace('${' . $match . '}', $this->get($match), $value);
			#}
			
			
			#check booleans...
			
			if($value == 'true'){
				
				$value = true;
				
			}
			
			if($value === 'false'){
				$value = false;
			}
			
			#echo "value = $value";
			$this->put($key, $value);
			
			#echo "loaded $key:$value<br>";
		}
	}
	
	
	private function searchAndReplace($key, $value){
            #do jsp-like replacements
            preg_match_all('/\${([a-zA-Z0-9._-]+)}*/', $value, $matches );
            $v = $value;
            foreach($matches[1] as $match){
                    if($match == $key){
                            //don't allow process to replace itself
                            continue;
                    }
                    $v = str_replace('${' . $match . '}', $this->get($match), $value);
            }
            return $v;
	}
	

    
}

?>
