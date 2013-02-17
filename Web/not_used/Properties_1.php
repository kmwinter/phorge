<?php

/**
 * Properties.php 
 * loads and stores properties from a file. understands java-like ${varname} notation
 *
 * 
 * Released under the BSD license. 
 * Copyright (c) 2006 Kirk WInters (kirk@kirkwinters.com)
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
 *   * Neither the name of kirkwinters.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
 *
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
*/
pminclude ('phorge:core.HashObject');
class Properties extends HashObject {
	
	
	
	
	/*public function loadFile($path){
		$this->load(file_get_contents($path));	
	}
	
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
			
			#preg_match_all('/\${([a-zA-Z0-9._-]+)}*
					/', $value, $matches );
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
	
	public function getProperty($key){
		return $this->get($key);
	}
	
	
	public function put($key, $value){
		parent::put($key, $this->searchAndReplace($key, $value));
	}
	
	private function searchAndReplace($key, $value){
		#do jsp-like replacements
		preg_match_all('/\${([a-zA-Z0-9._-]+)}*
			/', $value, $matches );
		$v = $value;
		foreach($matches[1] as $match){	
			if($match == $key){
				//don't allow process to replace itself
				continue;
			}
			$v = str_replace('${' . $match . '}', $this->get($match), $value);
		}
		return $v;	
	}*/
	
	
	
	
}



?>