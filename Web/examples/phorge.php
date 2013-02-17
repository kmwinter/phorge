<?php
include "/www/Phorge/init.php";

## this method without any parameters  will attempt to run whatever action/module was 
## parsed from the uri by the active UrlMask object

Phorge::displayUrlAction();

?>