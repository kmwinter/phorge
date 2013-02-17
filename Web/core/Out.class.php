<?php
class Out {


	private static $writer;

	
	public static function getWriter(){
		if(!self::$writer){			
			$class = pminclude(Framework::getConfigProperty(WRITER));		
			self::$writer = new $class();						
		}
		return self::$writer; 
	}

	
	
	public static function writeAction($viewResult){
        
		$writer = Out::getWriter();
		$result = $writer->writeAction($viewResult);
				
		return $result;
		 
			
	}
	
	
	public static function writeBlock($viewResult){
		$writer = Out::getWriter();
		$result = $writer->writeBlock($viewResult);
				
		return $result	;
	}
	
	public static function writeError($viewResult){
		$writer = Out::getWriter();
		$result = $writer->writeError($viewResult);
				
		return $result	;
	}
	
	/*private static function append(){
		
		$appendFile = Framework::getConfigProperty(APPEND_FILE);
		if(empty($appendFile)){
			return false;
		}
		
		require($appendFile);
	}*/
	
}	
?>