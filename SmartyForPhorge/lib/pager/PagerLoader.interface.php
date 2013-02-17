<?php

interface PagerLoader {


	
	public function getPageElements(HashMap $elements, $offset, $limit);
	
	public function getNumberOfElements(HashMap $elements);
	
}


?>