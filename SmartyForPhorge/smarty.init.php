<?php

$sfpDir = dirname(__FILE__);
Phorge::setConfigProperty('smarty.phorge.base', $sfpDir);
Phorge::setConfigProperty('sfp.base', $sfpDir);
//load smartyForPhorge properties
Phorge::loadConfigProperties($sfpDir . '/smarty.config.properties');

//add custom smartyForPhorge tree path
PackageManager::addNamespace('sfp', Phorge::getConfigProperty('smarty.pacakge.path'));
//add the smarty distro lib tree 

#smarty.distro.libs.dir will probably be set externally (not in smarty.config.properties)
#PackageManager::addNamespace('smartydistro', Phorge::getConfigProperty('smarty.distro.libs.dir'));

//is this necessary? (yes, for now)
#global $template;
#$template = Phorge::getConfigProperty('smarty.template');

//TODO get rid of global include ?
#pminclude('sfp:phorge.*')



?>
