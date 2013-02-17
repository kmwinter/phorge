<?php
interface PhorgeLogger {
	public function debug($message);
	
	public function notice ($message);
	
	public function warn($message);
	
	public function error($message);
	
	public function log($message, $level = null);
	
	
}

?>