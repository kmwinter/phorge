<?php

pminclude('lib:HashMap.ScalarHashMapSorter');
pminclude('lib:HashMap.HashFilter');

class MyHashFilter implements HashFilter {
	
	public function ask($key, $value){
		if($value < 0){
			return false;
		}
		
		return true;
	}
	
}

class HashMapTests extends UnitTestCase {

	private $values = array(6, 2, 1, 5, 10, -1, 0);
	private $map;
	
	public function __construct(){
		$this->map = new HashMap();
		$this->map->addArray($this->values);
	}
	
    public function testSort() {
		$values = $this->values;			
		#$map = new HashMap();
		#$map->addArray($values);
		$map = $this->map;
		$map->sortValues(new ScalarHashMapSorter());		
		$sorted = $map->toArray();		
		$sortedKeys = array_keys($sorted);		
		$this->assertEqual($sorted[$sortedKeys[0]], -1);
		
		
    }
	
	public function testFilter(){		
		$this->map->filter(new MyHashFilter());		
		$this->assertFalse($this->map->contains(-1));

	}
	
	public function testContains(){
		$key = 'key';
		$value = 'value';
		$map = new HashMap();
		$map->put($key, $value);
		
		$this->assertTrue($map->containsKey($key));
		$this->assertTrue($map->contains($value));
		
		$this->assertTrue(is_scalar(1121866987));
		
	}
   
}
?>