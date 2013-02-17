<?php
pminclude('phorge:core.interfaces.PageWriter');
class PhorgeWriter implements PageWriter {

	public function write($viewResult){
		if(! $viewResult){
			Logger::warn("No view result found");			
		}
		
		echo trim($viewResult);
		
		return null;
	}

	public function writeAction($viewResult) {
		return $this->write($viewResult);
	}
	
	public function writeBlock($viewResult){
		return $this->write($viewResult);
	}
	
	public function writeError($viewResult){
		return $this->write($viewResult);
	}

}

?>