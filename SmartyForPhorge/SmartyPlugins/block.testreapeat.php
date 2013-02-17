<?php
function smarty_block_testreapeat($params, $content, &$smarty, &$repeat) {
	
    
    #static $pager;
	#static $elements;
	static $count;
	
	
    if (!isset($content)) {    	
        /* start loop */
        #echo 'starting loop<br>';        
        $count = $params['count'];
        
        #$pager = $params['object'];
        #$elements = $pager->getPageElements();
        
		#reset($elements);       

    } else {
        /* next iteration */
        
        #next($elements);
		$count--;
    }

    $repeat = $count > 0; /* indicate loop */
    
    /* get current array-key */
    
    #$key = key($elements);
	#echo "key = $key of " . count($elements) . "<br>";
	
    /* set $repeat to false at the end of the loop, to true otherwise */
    #$repeat = $key !== null;

    #var_dump($smarty->_tag_stack);
    #print_r($smarty->_tag_stack);
    if($repeat) {
    	#echo "repeating <br>";
    	
        /* assign loop variable */                
        #$smarty->assign('element', $elements[$key]);
        #$content .=  smarty_block_pager($params, $content, $smarty, $repeat);
    	   
    	    	
    } else {
    	#echo 'not repeating<br>';
        /* free static variable */
        #unset($froms[$params['assign']]);
        #unset($elements);             
    }
	
    
	return $content;
    
}
?>