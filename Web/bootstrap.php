<?php
#start ticker
global $processingTime;
$processingTime = array();
$processingTime['Total']['start'] = microtime(true);
$processingTime['bootstrap']['start'] = microtime(true);


#determine framework path
global $frameworkRoot;
if(! empty($frameworkRoot)){
	define('FRAMEWORK_ROOT', $frameworkRoot);
}else {
	//save this for later
	$publicDir =  getcwd();
	
	chdir(dirname(__FILE__));
	$path =  realpath('../');
	define('FRAMEWORK_ROOT', $path);
	//set back to original
	chdir($publicDir);	
}

if(! is_dir(FRAMEWORK_ROOT)){
	trigger_error(FRAMEWORK_ROOT . ' is an invalid FRAMEWORK_ROOT');
}


$currentIncludePath = ini_get('include_path') . ':' . FRAMEWORK_ROOT;

//iclude any paths in .includepath file
$includePathFile = $publicDir . '/include.path';
if(file_exists($includePathFile)){	
    $includePath = str_replace("\n", ':', file_get_contents($includePathFile));
    //get current include path, add it to includePath string
    $includePath = $currentIncludePath . ":$includePath";
    
}else {
    $includePath = $currentIncludePath;
}
ini_set('include_path',  "$includePath");

//prep PacakgeManager
require_once('PackageManager/PackageManager.class.php');
//add Framework namespace
PackageManager::addNamespace('framework', FRAMEWORK_ROOT);
//add phorge namespace
PackageManager::addNamespace('phorge', FRAMEWORK_ROOT . '/Web' );

//add all paths in the .includepath file to the 'lib' namespace
//includes existing php include path
foreach(split(':', $includePath) as $path){
    PackageManager::addNamespace('lib', $path);
}

#include HashMap class
pminclude('framework:HashMap.HashMap');
pminclude('framework:HashMap.Properties');


//include IOC container
pminclude('framework:IOC.IocContainer');

//include main Phorge class
pminclude('phorge:core.Phorge');


#load constant mappings:
$mappings = new Properties();
$mappings->loadProperties(FRAMEWORK_ROOT . '/Web/config.mappings');

foreach($mappings->toArray() as  $mapping => $constant){
	define($constant, $mapping);
}


##Load framework configuration parameters
$config = new Properties();
$config->put('framework.root', FRAMEWORK_ROOT);
$config->loadProperties(FRAMEWORK_ROOT . '/Web/config.properties');
Phorge::addConfigProperties($config);

//set the PUBLIC_DIR config property to the directory of the script that's calling Phorge
Phorge::setConfigProperty(PUBLIC_DIR, getcwd());

#====================================================================
//time display options
define('SHOW_TIME_LOG', 'log');
define('SHOW_TIME_PRINT', 'print');
define('SHOW_TIME_OFF', 'off');

#generic default value
define('DEFAULT_VALUE', Framework::getConfigProperty(DEFAULT_KEY));

#generic keys
define('MODULE', Framework::getConfigProperty(MODULE_KEY));
define('ACTION', Framework::getConfigProperty(ACTION_KEY));
define('ID', Framework::getConfigProperty(ID_KEY));
define('VIEW', Framework::getConfigProperty(VIEW_KEY));
define('BLOCK', Framework::getConfigProperty(BLOCK_KEY));
define('EXCEPTION', Framework::getConfigProperty(EXCEPTION_KEY));


$processingTime['bootstrap']['stop'] = microtime(true);

#====================================================================


?>