<?php

/**
 * HashObject.class.php 
 * Java HashMap-like object for PHP
 *
 * 
 * Released under the BSD license. 
 * Copyright (c) 2006 Kirk Winters (kirk@kirkwinters.com)
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
pminclude("phorge:exceptions.GeneralException");

class HashObject implements Iterator {
	
	private $hashTable = array();
	private $valid = true;
	
	public function put($key = null, $value, $retainCase = true){
		if(is_scalar($value)){
			$value = $this->searchAndReplace($key, $value);
		}

		if($key){

			if(is_scalar($key)){ 
				if( !$retainCase){
					$key = strtolower($key);
				}			
			}
			
			$this->hashTable[$key] = $value;
		}else {
			$this->hashTable[] = $value;
		}
					
	}
	
	
	public function get($key){
		if(! key_exists($key, $this->hashTable)){
			return null;
		}
		
		return $this->hashTable[$key];
	}
	
	public function destroy($key){
		unset($this->hashTable[$key]);
	}
	
	/*public function destroyAll(){
		$this->hashTable = array();
	}*/
	
	
	public function containsKey($key, $retainCase = true){
		if(is_scalar($key) && !$retainCase){
			$key = strtolower($key);
		}
		return key_exists($key, $this->hashTable);
	}
	
	public function containsValue($key, $retainCase = true){
		if(is_scalar($key) && !$retainCase){
			$key = strtolower($key);
		}
		
		
		if(! key_exists($key, $this->hashTable)){
			return false;
		}
		
		
		if(is_array($this->hashTable[$key])){
			
			return count($this->hashTable[$key]);
		}
		
		if(! empty($this->hashTable[$key]) ){
			return trim($this->hashTable[$key]);
		}
		
		return null;
	}
	
	public function contains($value){
		
		return in_array($value, $this->hashTable);
	}
	
	public function combine(HashObject $hash){
		foreach($hash as $key => $value){
			$this->put($key, $value);
		}
	}
	
	
	public function getRange($start, $end){
		return array_slice($this->hashTable, $this->getOffset(), $this->getLimit(), true);
	}
	
	public function getAll(){
		return $this->hashTable;
	}
	
	
	
	public function toString(){
		$string = "";
		foreach($this as $key => $value){
			if(is_object($value)){				
				$string .= "[$key] : " . $value->toString() . " <br>";
				continue;
			}
			$string .= "$key : $value <br>";
			
		}
		return $string;
	}
	
	public function count(){
		return count($this->hashTable);
	}
	
	
	public function toArray(){
		return $this->hashTable;
	}
	
	public function addArray($source){
		if(! is_array($source)){
			throw new GeneralException("source is not an array");
		}
		
		foreach($source as $key => $value){
			$this->put($key, $value);
		}
	}
	
	public function sortValues(HashComparator $comparator) {
		$hashKeys = array_keys($this->hashTable);
					
		for($i = 1; $i < count($hashKeys) - 1; $i++){
			for($j = count($hashKeys) - 1; $j >= $i + 1; $j-- ){					
				
				$value1 = $this->hashTable[$hashKeys[$j]];				
				$value2 = $this->hashTable[$hashKeys[$j - 1]];
				
				$compareResult = $comparator->compare($value1,$value1);
				
				if($compareResult < 1){
					//swap
					$tmpIndex = $j - 1;
					$tmpKey = $hashKeys[$tmpIndex];
					$tmpValue = $this->hashTable[$tmpKey];
					
					$this->hashTable[$tmpKey] = $this->hashTable[$hashKeys[$j]];
					$hashKeys[$tmpIndex] = $hashKeys[$j]; 
					
					$this->hashTable[$hashKeys[$j]] = $tmpValue;
					$hashKeys[$j] = $tmpKey;

					
				}
				

			}
			
			
		}		
		
	}
	
	public function sortKeys(HashComparator $comparator) {
		$hashKeys = array_keys($this->hashTable);
				
		for($i = 1; $i < count($hashKeys) - 1; $i++){
			for($j = count($hashKeys); $j >= $i + 1; $j-- ){
				$compareResult = $comparator->compare($hashKeys[$j], $hashKeys[$j - 1]);
				if($compareResult < 1){
					//swap
					$tmpIndex = $j - 1;
					$tmpKey = $hashKeys[$tmpIndex];
					$tmpValue = $this->hashTable[$tmpKey];
					
					$this->hashTable[$tmpKey] = $this->hashTable[$hashKeys[$j]];
					$hashKeys[$tmpIndex] = $hashKeys[$j]; 
					
					$this->hashTable[$hashKeys[$j]] = $tmpValue;
					$hashKeys[$j] = $tmpKey;
					
				}
			}
			
			
		}
	}
	
	public function filter(HashFilter $filter){
		$filteredList = array();
		
		foreach($this->hashTable as $key => $value){
			if($filter->ask($key, $value)){
				$filteredList[$key] = $value;
			}			
		}
		
		$this->hashTable =  $filteredList;
	}
	
	
	/* Iterator Methods: 
	==================================================================*/
	public function rewind(){
   		$this->valid = (FALSE !== reset($this->hashTable));
 	}

   /**
   * Return the current array element
   */
 	public function current(){
   		return current($this->hashTable);
 	}

   /**
   * Return the key of the current array element
   */

   public function key(){
   		return key($this->hashTable);
 	}

   /**
   * Move forward by one
   * PHP's next() returns false if there are no more elements
   */
 	public function next(){
   		$this->valid = (FALSE !== next($this->hashTable));
 	}

   /**
   * Is the current element valid?
   */
 	public function valid(){
   		return $this->valid;
 	} 
 	
 	
 	/* Iterator Methods: 
	==================================================================*/
 	
	
	##====== Properties methods ======================

	public function loadFile($path){
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
	
	/*public function getProperty($key){
		return $this->get($key);
	}
	
	
	public function put($key, $value){
		parent::put($key, $this->searchAndReplace($key, $value));
	}*/
	
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