<?php
 

define('_PM_CONFIG_PATH', dirname(__FILE__) . '/pm.properties');

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
	
	const DEFAULT_NAMESPACE = '_default';
	private static $instance;
	
	private $properties;
	private $namespaces = array();
	//private $defaultKey ;
	private $fileTypes = array();
	
	
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new PackageManager();
		}
		
		return self::$instance;
	}
	
	
	
	
	
	public function __construct(){
		
		//went to using ini_file instead of my more robust HashMap/Properties file
		//in order to remove dependencies. configuration needs for this class are simplistic anyway
		$this->properties = parse_ini_file(_PM_CONFIG_PATH);
		
		//make default tree
		$this->namespaces[self::DEFAULT_NAMESPACE][] = $this->properties['default.namespace'];
		
		if(!key_exists('include.file.types',$this->properties)){
			throw new PackageManagerException("No file types found");
		}
		
		$this->fileTypes = split(',', $this->properties['include.file.types']);
	}
	
	
	
	
	public static function translatePackage($packageString){
		global $log;
		$namespaces = PackageManager::getNamespaces();
		if(strpos($packageString, ':') !== false){
			
			list($tree, $package) = split(':', $packageString);
			
			if(! key_exists($tree, $namespaces)){
				
				$tree = self::DEFAULT_NAMESPACE;
			}
		}else {
			$tree = self::DEFAULT_NAMESPACE;
			$package = $packageString;
		}
		$pathArray = array();
		foreach($namespaces[$tree] as $treePath){
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
			
			if(! key_exists($tree, $this->namespaces)){
				throw new PackageManagerException("namespace not defined: $tree");
				#$tree = self::DEFAULT_NAMESPACE;
			}
		}else {
			$tree = self::DEFAULT_NAMESPACE;
			$package = $packageString;
		}
		
				
		
		
		if(strpos($package, '*') === (strlen($package) - 1)) {			
			//is a dir
			$package = rtrim($package, '/*');
			foreach($this->namespaces[$tree] as $treePath){
				
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
			
			foreach($this->namespaces[$tree] as $treePath){
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
	
	public static function addNamespace($namespace, $path){
		self::getInstance()->addNamespaceImpl($namespace, $path);
	}
	
	protected function addNamespaceImpl($namespace, $path){
		## don't add existing trees
		if(key_exists($namespace, $this->namespaces)){
			if(! in_array($path, $this->namespaces[$namespace])){
				$this->namespaces[$namespace][] = $path;				
			}
		}else {
			## new tree
			$this->namespaces[$namespace] = array($path);
		}
		
		#print_r($this->namespaces);
		
	}
	
              
        
    public static function addDefaultNamespacePath($path){
		self::getInstance()->addDefaultNamespaceImpl($path);
	}
	
	public function addDefaultNamespaceImpl($path){
		$this->addNamespaceImpl(self::DEFAULT_NAMESPACE, $path);
		//$this->namespace[self::DEFAULT_TREE_NAME][] = $path;
	}


    public static function namespaceExists($namespace){
        return self::getInstance()->namespaceExistsImpl($namespace);
    }


    public function namespaceExistsImpl($namespace){
        return key_exists($namespace, $this->namespaces);
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

    public static function getNamespaces(){
            return self::getInstance()->$namespaces;
    }

    static function printNamespaces(){
        $instance = self::getInstance();
        foreach($instance->namespaces as $name => $paths){
            foreach($paths as $path){
                echo "$name: $path<br>";
            }
        }
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