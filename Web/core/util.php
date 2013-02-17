<?php

/*function moduleExists($moduleName){	
	return file_exists(MODULE_DIR . "/$moduleName");
}
*/
function lcfirst($str) {
   return strtolower(substr($str, 0, 1)) . substr($str, 1);
}


function getDirectoryList($directory, $fileTypesArray = array()){
	
	$defaultFileTypesArray = array('.php', '.inc', '.tpl');
	
	$files = array();		
	if(empty($fileTypesArray)){
		$fileTypesArray = $defaultFileTypesArray;
	}
	
	
	if (is_dir($directory)) {
		if ($dh = opendir($directory)) {
	   		while (($file = readdir($dh)) !== false) {
	   			if( in_array(strrchr($file, '.'), $fileTypesArray)){
	   				//$baseValue = rtrim($file, '.php');
	           		//$files[] = $baseValue;
	           		
	           		$files[] = $file;	       
	           	}   		
	           
	       	}#end while
	       	
       		closedir($dh);
   			
	   	}else {
	   		throw new GeneralException("Could not open directory $directory for reading");	
	   	
	  	}
	}else 	{
		throw new GeneralException("Not a directory: $directory");			
	}
	
	return $files;
}


function loadLibrary($directory, $verbose = false){
	try {
		$files = getDirectoryList($directory, array('.php', '.inc'));
	}catch (Exception $e){
		throw $e;
	}
	
	foreach($files as $file){
		if($verbose){
			echo ('Loading file ' . rtrim($directory, '/') . "/$file" . "\n");
		}
		include_once rtrim($directory, '/') . "/$file";	
	}
}






/*function resolveActionPath($moduleName, $actionName){
	$actionObject = ucfirst($moduleName) . ucfirst($actionName);
	return MODULE_DIR . "/$moduleName/Actions/$actionObject.php";
}


function resolveViewPath($moduleName, $viewName){
	return MODULE_DIR . "/$moduleName/Views/$viewName.php";
}*/



?>