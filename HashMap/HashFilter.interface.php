<?php


/**
 * The HashFilter interface is used to filter values from a hashMap. The ask method should return a boolean value.
 *  
 * When $map->filter(new FooFilter()); is called, the FooFilter->ask($key, $value) method
 * will be called for each element in the map. If the method result is true, the element will be kept, if the result
 * returned is false the element will be filtered and removed. 
 * 
 *  If you wanted to filter out all values greater than 100 you could:
 *  
 *  public function ask($key, $value){
 *  	if($value > 100)
 *  		return false;
 *  
 *  	return true;
 *  }
 * 
 * beacuse the key are passed into the ask method, you can similarly filter on keys. 
 * 
 */
interface HashFilter {
	
	public function ask($key, $value);
	
}
?>