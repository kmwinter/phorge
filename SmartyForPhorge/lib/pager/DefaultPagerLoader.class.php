<?php




class DefaultPagerLoader implements PagerLoader{
    
    public function getPageElements(HashMap $elements, $offset, $limit){
		return $elements->getRange($offset, $limit);		
    }
    
    
    public function getNumberOfElements(HashMap $elements){
        return $elements->count();
    }	
		
	
}

?>