<?php
interface UrlMask {
	
	/**
	 * Interface UrlMask
	 * 
	 * It's the UrlMask's job to populate the Request with the MODULE, and optional ACTION and ID values.
	 * This is done via the UrlService::parse() method which, in turn calls the defined UrlMask's
	 * parse method to actually perform the dirty work. 
	 * 
	 * 
	 * In addition to parsing the MODULE, ACTION, and ID values, the UrlMask is also responsible for creating
	 * an encoded URL string that works with the web server configuration (e.g. mod_rewrite)  
	**/	

	#public static function getUrl($location, $action = null, $properties = array(), $subAction = null);
	
	
	/**
	 * Return URL string 
	 *
	 * @param String $location
	 * @param String $action
	 * @param String $id
	 * @param Array $properties
	 * @param String $anchor
	 */
	#public function getUrlString($module, $action = null, $id = null, $properties = array(), $subAction = null);
	public function getUrlString($path, $properties = array(), $anchor = null);
	public function getActionUrlString($action, $module = null, $id=null, $properties = array(), $anchor = null);

	
	/**
	 * Returns a URL object
	 * 
	 * @param String $location
	 * @param String $action
	 * @param String $id
	 * @param Array $properties
	 * @param String $subAction
	**/
	#public function getUrl($module, $action = null, $id = null, $properties = array(), $subAction = null); 	
	public function parse(Request $request);
			
	public function getModule();	
	public function getAction();
	
	
	
}


?>