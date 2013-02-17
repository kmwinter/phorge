<?php
function smarty_block_pager($params, $content, &$smarty, &$repeat) {
	
    
    static $pager;
	static $elements;
	
	$elementVar = $params['assign'];
	$keyVar = $params['assignKey'];
	
	if(empty($elementVar)){
		$elementVar = 'element';
	}
	


	if(empty($keyVar)){
		$keyVar = 'key';
	}
	
    if (!isset($content)) {    	
        /* start loop */        
        $pager = $params['object'];
        
        #require_once( dirname(__FILE__)."/function.pagerHeader.php");        
        #$content = smarty_function_pagerHeader(array('pager'=>$pager), $smarty);
        
        //TODO Fix:
        #echo $content;
        
        $elements = $pager->getPageElements(); #->toArray();
          
        if(! is_array($elements)){
        	throw new GeneralException("Pager elements is not an array");
        }
        
		reset($elements);       

    } else {
        /* next iteration */        
        next($elements);
		#$count--;
    }

    
    
    /* get current array-key */    
    $key = key($elements);
    /* set $repeat to false at the end of the loop, to true otherwise */
    $repeat = $key !== null;
    
    #$repeat = $count > 0; /* indicate loop */
    
    
	#echo "key = $key of " . count($elements) . "<br>";
	
    
    

    #var_dump($smarty->_tag_stack);
    #print_r($smarty->_tag_stack);
    if($repeat) {
    	#echo "repeating <br>";    	
        /* assign loop variable */                
        
        $smarty->assign($elementVar, $elements[$key]);
        $smarty->assign($keyVar, $key);
        #$content .=  smarty_block_pager($params, $content, $smarty, $repeat);
    	   
    	    	
    } else {
    	#echo 'not repeating<br>';
        /* free static variable */        
        unset($elements);             
    }
	
    
	return $content;
    
}
?>