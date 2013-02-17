<?php

function smarty_function_displayTemplate($params, &$smarty){
	$display = $params['template'];
	$assign = $params['var'];
	$module = $params['module'];
	$object = $params['object'];
	$objName = $params['objectName'];
	
	
	#$ps = new PhorgedSmarty();
	
	if($object){
		if(!$objName){
			$objName = 'object';	
		}
		$smarty->assign($objName, $object);
		#$ps->assign($objName, $object);
	}
    
    $module = Phorge::getModule($module);
    $dispatcher = $module->getViewDispatcher();
    foreach($dispatcher->getViewDirectory() as $directory){
        
        if(file_exists("$directory/$display")){
            $path ="$directory/$display";
        }
    }

    if($path){
        $content = $smarty->fetch($path);
        if($assign){

            $smarty->assign($assign, $content);

        }else{
            return trim($content);
            #return trim($content);
        }
	}else {
        throw new Exception("template $display not found");
    }
	
	
}

?>