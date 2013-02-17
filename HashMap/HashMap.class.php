<?php

/**
 * HashMap.class.php
 * Java HashMap-like object for PHP
 *
 *
 * Released under the BSD license.
 * Copyright (c) 2008 Kirk Winters (kirk@kirkwinters.com)
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


class HashMap implements Iterator {

    private $retainKeyCase = true;
    private $hashTable = array();
    private $valid = true;

    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    /**
     * put a key-value pair into the map
     *
     * @return
     * @param object $key[optional]
     * @param object $value
     */
    public function put($key = null, $value) {

        if($key) {

            if(is_scalar($key)) {
                if( !$this->retainKeyCase) {
                    $key = strtolower($key);
                }
            }

            $this->hashTable[$key] = $value;
        }else {
            $this->hashTable[] = $value;
        }

    }

    /**
     * get a value from the map given the associated key
     *
     * @return Mixed value
     * @param object $key
     */
    public function get($key) {
        if(! $this->retainKeyCase) {
            $key = strtolower($key);
        }
        
        if(! key_exists($key, $this->hashTable)) {
            return null;
        }

        return $this->hashTable[$key];
    }



    public function setValues($values){
        if(! is_array($values)){
            throw new Exception("tried setting non-array to values in HashMap");
        }
        #$this->hashTable = array_merge($this->hashTable, $values);
        $this->hashTable = $values;

    }

    /**
     * use HashMap::remove instead
     *
     * @deprecated
     * @param mixed $key
     *
     */
    public function destroy($key) {
        $this->remove($key);
    }


    /**
     * remove a key-value pair from the map with given key
     *
     *
     * @param object $key
     */
    public function remove($key) {
        unset($this->hashTable[$key]);
    }


    /**
     * Returns true if given key is present in the map, false otherwise
     *
     * @return boolean
     * @param object $key
     */
    public function containsKey($key) {
        if(!is_scalar($key)) {
            throw new Exception("HashMap key must be scalar");
        }

        if(!$this->retainKeyCase) {
            $key = strtolower($key);
        }

        if(empty($key)) {
            throw new Exception("Empty key passed into HashMap::containsKey");
        }

        return array_key_exists($key, $this->hashTable);
    }

    /**
     * returns true if given value is in map
     *
     * use HashMap::contains instead
     *
     * @deprecated
     * @return boolean
     * @param Mixed $value
     */
    public function containsValue($value) {
        return $this->contains($value);
    }


    /**
     * returns true if given value is in map, false otherwise
     *
     * @return
     * @param object $value
     */
    public function contains($value) {

        return in_array($value, $this->hashTable);
    }

    /**
     * combine the elements in given HashMap with the contents of this HashMap
     *
     * @param HashMaop $hash
     */
    public function combine(HashMap $hash) {
        foreach($hash as $key => $value) {
            $this->put($key, $value);
        }
    }


    /**
     * Return an array slice with provided start, end parameters
     *
     * @return array
     * @param scalar $start
     * @param scalar $end
     */
    public function getRange($start, $end) {
        return array_slice($this->hashTable, $start, $end, true);
    }


    /**
     * Return all element in map as an associated array. Same as HashMap::toArray()
     *
     * @return array
     */
    public function getAll() {
        return $this->hashTable;
    }



    /**
     * Returns a string list of all elements in map. The end line paramter will be appended to the end
     * of each key-value pair.
     *
     * @return String
     * @param String $endLine[optional]
     */
    public function toString($endLine = "\n") {
        $string = "";
        foreach($this as $key => $value) {
            if(is_object($value)) {
                $string .= "[$key] : " . $value->toString() . $endLine;
                continue;
            }
            $string .= "$key : $value $endLine";

        }
        return $string;
    }


    /**
     * return the number of elements in this map
     *
     * @return int
     */
    public function count() {
        return count($this->hashTable);
    }


    /**
     * return an associative array of all elements in the map
     *
     * @return array
     */
    public function toArray() {
        return $this->hashTable;
    }

    /**
     * Add all elements in an array to this map
     *
     * @param array $source
     */
    public function addArray($source) {
        if(! is_array($source)) {
            throw new Exception("source is not an array");
        }

        foreach($source as $key => $value) {
            $this->put($key, $value);
        }
    }


    /**
     * Sort entries in this map by value with given HashComparator implementation
     * Sort direction is an optional parameter. Elegible values are HashMap::SORT_ASC or
     * HashMap::SORT_DESC. SORT_ASC is default.
     *
     *
     * @param HashComparator $comparator
     * @param const $direction[optional]
     */
    public function sortValues(HashComparator $comparator, $direction = self::SORT_ASC) {

        $hashKeys = array_keys($this->hashTable);
        $numberOfKeys = count($hashKeys);

        for ($i=0; $i < $numberOfKeys - 1; $i++) {
            for ($j=0; $j < $numberOfKeys - 1; $j++) {

                $value1 = $this->hashTable[$hashKeys[$j]];
                $value2 = $this->hashTable[$hashKeys[$j+1]];
                $result = $comparator->compare($value1,$value2);

                if($direction == self::SORT_ASC) {
                    $test = $result > 0;
                }else {
                    $test = $result < 0;
                }

                if ($test) {

                    $tmpIndex = $j + 1;

                    $tmpKey = $hashKeys[$tmpIndex];
                    $tmpValue = $this->hashTable[$tmpKey];

                    $this->hashTable[$tmpKey] = $this->hashTable[$hashKeys[$j]];
                    #$hashKeys[$tmpIndex] = $hashKeys[$j];

                    $this->hashTable[$hashKeys[$j]] = $tmpValue;
                #$hashKeys[$j] = $tmpKey;


                }
            }

        }



    }


    /**
     * Sort entries in this map by key with given HashComparator implementation
     * Sort direction is an optional parameter. Elegible values are HashMap::SORT_ASC or
     * HashMap::SORT_DESC. SORT_ASC is default.
     *
     *
     * @param HashComparator $comparator
     * @param const $direction[optional]
     */
    public function sortKeys(HashComparator $comparator, $direction = self::SORT_ASC) {
        $hashKeys = array_keys($this->hashTable);
        $numberOfKeys = count($hashKeys);

        for ($i=0; $i < $numberOfKeys - 1; $i++) {
            for ($j=0; $j < $numberOfKeys - 1; $j++) {

                $value1 = $hashKeys[$j];
                $value2 = $hashKeys[$j+1];
                $result = $comparator->compare($value1,$value2);

                if($direction == self::SORT_ASC) {
                    $test = $result > 0;
                }else {
                    $test = $result < 0;
                }

                if ($test) {

                    $tmpIndex = $j + 1;

                    $tmpKey = $hashKeys[$tmpIndex];
                    $tmpValue = $this->hashTable[$tmpKey];

                    $this->hashTable[$tmpKey] = $this->hashTable[$hashKeys[$j]];
                    #$hashKeys[$tmpIndex] = $hashKeys[$j];

                    $this->hashTable[$hashKeys[$j]] = $tmpValue;
                #$hashKeys[$j] = $tmpKey;


                }
            }

        }


		/*
		$hashKeys = array_keys($this->hashTable);
				
		for($i = 1; $i < count($hashKeys) - 1; $i++){
			for($j = count($hashKeys); $j >= $i + 1; $j-- ){
				
				$result = $comparator->compare($hashKeys[$j], $hashKeys[$j - 1]);
							
				if($direction == self::SORT_ASC){
					$test = $result > 0;
				}else {
					$test = $result < 0;
				}
				
				if($test){
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
		*/
    }

    /**
     * Filter values from this map using provided HasFilter implementation.
     *
     * @param HashFilter $filter
     */
    public function filter(HashFilter $filter) {
        $filteredList = array();

        foreach($this->hashTable as $key => $value) {
            if($filter->ask($key, $value)) {
                $filteredList[$key] = $value;
            }
        }

        $this->hashTable =  $filteredList;
    }


	/* Iterator Methods: 
	==================================================================*/
    public function rewind() {
        $this->valid = (FALSE !== reset($this->hashTable));
    }

    /**
     * Return the current array element
     */
    public function current() {
        return current($this->hashTable);
    }

    /**
     * Return the key of the current array element
     */

    public function key() {
        return key($this->hashTable);
    }

    /**
     * Move forward by one
     * PHP's next() returns false if there are no more elements
     */
    public function next() {
        $this->valid = (FALSE !== next($this->hashTable));
    }

    /**
     * Is the current element valid?
     */
    public function valid() {
        return $this->valid;
    }


 	/* Iterator Methods: 
	==================================================================*/

}


?>