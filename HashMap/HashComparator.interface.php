<?php


/**
 * Comparator interface based on the java Comparator object. 
 * 
 * http://java.sun.com/j2se/1.5.0/docs/api/java/util/Comparator.html
 * 
 * Two values are given to the comparator implementation. It's the comparator's
 * job to decide how they relate to each other.
 * 
 * generally:
 * if $value1 < $value2, return -1
 * if $value1 > $value1, return 1
 * if $value1 === $value2, return 0
 * 
 * See the ScalarHashMapSorter example for a basic scalar implementation of this idea.
 * 
 * This is abstracted out so that objects can be compared based on their properties. For example
 * if you have a Foo object that has a date property, you can create a FooComparator implementation
 * that does something like :
 * 
 * class FooComparator implements HashComparator {
 * 	public compare($value1, $value2){
 * 		if(!($value1 instanceof Foo && $value2 instanceof Foo)) {
 *  		throw new Exception();
 *   	}
 *   
 *  	 $date1 = $value1->getDate();
 * 	 	$date2 = $value2->getDate();
 *  
 *  	if($date1 === $date2){
 *  		return 0;
 *  	}
 *  
 *   	if($date1 < $date2){
 *   		return -1;
 *   	}
 *   
 *   	return 1;
 *   }
 *  }
 *   
 *   
 *  $map->sortValues(new FooComparator);
 * 
 */
interface HashComparator {
	
	public function compare($value1, $value2);	
	
	
}
?>