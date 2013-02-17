<?php
/**
 * to be run from the commandline
 *
 */
require('bootstrap.php');


pminclude('lib:simpletest.unit_tester');
pminclude('lib:simpletest.reporter');


class PhorgeTestSuite extends TestSuite {
    function phorgeTestSuite(){
        $this->TestSuite('Phorge Tests');        
        $testFiles = $this->getTestFiles();
        foreach($testFiles as $file){
            $this->addTestFile($file);
        }


    }


    private function getTestFiles(){
        $directory = Phorge::getConfigProperty('framework.root') . '/tests';
        $files = array();
        if (is_dir($directory)) {
            if ($dh = opendir($directory)) {
                while (($file = readdir($dh)) !== false) {
                    if(! ($file == '.' || $file=='..' || substr($file, 0, 1) == '.') ){
                        $files[] = "$directory/$file";
                    }

                }
                closedir($dh);

            }else {
                throw new Exception("Could not open directory $directory for reading");

            }
        }else 	{
            throw new Exception("Not a directory: $directory");
        }

        return $files;
    }

}

$frameworkRoot = Phorge::getConfigProperty('framework.root');
$appRoot = "$frameworkRoot/examples";
Phorge::configureApplication($appRoot, 'example.xml');

$test = new PhorgeTestSuite();

Phorge::initialize();
$test->run(new TextReporter());


?>
