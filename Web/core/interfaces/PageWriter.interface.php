<?php
interface PageWriter {

	#public function write($viewResult);
	public function writeAction($viewResult);
	public function writeBlock($viewResult);
	public function writeError($viewResult);

}
?>