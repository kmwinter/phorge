<?php
 

define('_PM_CONFIG_PATH', dirname(__FILE__) . '/config.properties');

//TODO:: Abstract out windows/unix directory delimiters (\ vs /)
//TODO:: avoid infinite loops where a file tries to .* on its own directory

class PackageManagerException extends Exception {
	private $package;
	public function __construct($message, $package = null){
		parent::__construct($message);
		$this->package = $package;
	}
	
	public function getPackage(){
		return $this->package;
	}
}

//this guy is really cool. 
//Allows to point out at what point in the recursive loading process 
//an exception occured
class NestedPackageManagerException extends Exception {
	
}

class PackageManager {
	
	const DEFAULT_TREE_NAME = '_default';
	private static $instance;
	
	private $properties;
	private $sourceTrees = array();
	//private $defaultKey ;
	private $fileTypes = array();
	
	
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new PackageManager();
		}
		
		return self::$instance;
	}
	
	
	
	
	
	public function __construct(){
		
		//went to using ini_file instead of my more robust HashObject/Properties file 
		//in order to remove dependencies. configuration needs for this class are simplistic anyway
		$this->properties = parse_ini_file(_PM_CONFIG_PATH);
		
		//make default tree
		$this->sourceTrees[self::DEFAULT_TREE_NAME][] = $this->properties['default.tree'];
		
		if(!key_exists('include.file.types',$this->properties)){
			throw new PackageManagerException("No file types found");
		}
		
		$this->fileTypes = split(',', $this->properties['include.file.types']);
	}
	
	
	public static function getTrees(){
		return self::getInstance()->sourceTrees;
	}
	
	public static function translatePackage($packageString){
		global $log;
		$sourceTrees = PackageManager::getTrees();
		if(strpos($packageString, ':') !== false){
			
			list($tree, $package) = split(':', $packageString);
			
			if(! key_exists($tree, $sourceTrees)){
				
				$tree = self::DEFAULT_TREE_NAME;
			}
		}else {
			$tree = self::DEFAULT_TREE_NAME;
			$package = $packageString;
		}
		$pathArray = array();
		foreach($sourceTrees[$tree] as $treePath){
			$log->log("source tree for package $tree: $treePath ($packageString)");
			$pathArray[] = rtrim($treePath, '/') . '/' . str_replace('.', '/', $package);
		}
		return $pathArray;
	}
	
	
	public static function includePackage($package){
		return self::getInstance()->includePackageImpl($package);
	}
		
	protected function includePackageImpl($packageString){
		
		if(strpos($packageString, ':') !== false){
			
			list($tree, $package) = split(':', $packageString);
			
			if(! key_exists($tree, $this->sourceTrees)){
				
				$tree = self::DEFAULT_TREE_NAME;
			}
		}else {
			$tree = self::DEFAULT_TREE_NAME;
			$package = $packageString;
		}
		
				
		
		
		if(strpos($package, '*') === (strlen($package) - 1)) {			
			//is a dir
			$package = rtrim($package, '/*');
			foreach($this->sourceTrees[$tree] as $treePath){
				
				$path = rtrim($treePath, '/') . '/' . str_replace('.', '/', $package);
				
				
				
				if(!is_dir($path)){
					continue;
				}
				$fileList = $this->getDirectoryList($path);
				$classes = array(); 
				foreach($fileList as $file){
					//echo "including $path/$file<br>";
					try {
						//echo "$path/$file<br>\n";
						require_once "$path/$file";
					}catch(PackageManagerException $e){
						throw new NestedPackageManagerException("PackageManagerException occured when loading $path/$file: " . $e->getMessage());
					}
					$classes[] = substr($file, 0, strpos($file, '.'));								 	
				}
								
			}
			
			if(empty($classes)){
				throw new PackageManagerException("$path is not a valid package directory", $packageString);
			}
			
			return $classes;
			
		}else {
			//is a file
			$included = array();
			
			foreach($this->sourceTrees[$tree] as $treePath){
				$path = rtrim($treePath, '/') . '/' . str_replace('.', '/', $package);			
				
				foreach($this->fileTypes as $ext){
					$ext = trim($ext);
					//append file and see if file exists
					#echo "$path.$ext<br>\n";
					$file_path = "$path.$ext";
					if(file_exists("$file_path")){
						
						try {
							#echo " ...including $path.$ext in tree $tree";																					
							require_once $file_path; 							
							#echo ".... done\n<br>";							
							$included = ltrim(strrchr($path, '/'), '/');
							#$included[]= ltrim(strrchr($path, '/'), '/');
							
						}catch(PackageManagerException $e){
							throw new NestedPackageManagerException("PackageManagerException occured when loading $path.$ext: " . $e->getMessage());
						}
						
						break;											
						
					}
					if($included){
						break;
					}
				}
				
			}
			
			if(empty($included)){
				throw new PackageManagerException("$path is not a valid package", $packageString);
			}

			/*if(count($included) == 1){			
				return current($included);
			}*/
			
			return $included;
		}
		
		
	}
	
	public static function addTree($treeName, $path){
		self::getInstance()->addTreeImpl($treeName, $path);
	}
	
	protected function addTreeImpl($treeName, $path){
		## don't add existing trees
		if(key_exists($treeName, $this->sourceTrees)){
			if(! in_array($path, $this->sourceTrees[$treeName])){
				$this->sourceTrees[$treeName][] = $path;				
			}
		}else {
			## new tree
			$this->sourceTrees[$treeName] = array($path);
		}
		
		#print_r($this->sourceTrees);
		
	}
	
	public function setDefaultTree($path){
		self::getInstance()->setDefaultTreeImpl($path);
	}
	
	public function setDefaultTreeImpl($path){		
		$this->addTreeImpl(self::DEFAULT_TREE_NAME, $path);
		//$this->sourceTrees[self::DEFAULT_TREE_NAME][] = $path;
	}
	
	/**
	 * returns an array of filenames in provided directory path
	 * 
	 * @param String directory path
	 * @return Array of files in that directory
	 */
	private function getDirectoryList($directory){
	
		
		//$defaultFileTypesArray = array('.php', '.inc', '.tpl');
		
		$fileTypesArray  = $this->fileTypes;
		
		
		
		$files = array();		
				
		if (is_dir($directory)) {
			if ($dh = opendir($directory)) {
		   		while (($file = readdir($dh)) !== false) {
		   			if( in_array(trim(strrchr($file, '.'), '.'), $fileTypesArray)){
		   				//$baseValue = rtrim($file, '.php');
		           		//$files[] = $baseValue;
		           		
		           		$files[] = $file;	       
		           	}   		
		           
		       	}#end while
		       	
	       		closedir($dh);
	   			
		   	}else {
		   		throw new PackageManagerException("Could not open directory $directory for reading");	
		   	
		  	}
		}else 	{
			throw new PackageMangerException("Not a directory: $directory");			
		}
		
		return $files;
	} 
		
	
}



/**
 * simple function that interfaces with the PackageManager in order
 * to simplify the many, many include calls this guy creates 
 */

function pminclude($package){
	return PackageManager::includePackage($package);
}



?>