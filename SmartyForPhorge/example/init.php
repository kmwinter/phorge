<?php
//load smartyForPhorgeInit
chdir(dirname(__FILE__));
$path =  realpath('../') . '/smarty.init.php';
require $path;

//application specific smarty properties
#Phorge::setConfigProperty('smarty.template.dir', '${sfp.base}/example/Views');
Phorge::setConfigProperty('smarty.compile.dir', '${sfp.base}/example/etc');
Phorge::setConfigProperty('smarty.config.dir', '${sfp.base}/example/etc');
Phorge::setConfigProperty('smarty.cache.dir', '${sfp.base}/example/etc');
Phorge::setConfigProperty('smarty.template.file', '${sfp.base}/Views/template.tpl');


?>