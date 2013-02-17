<?php

# include Phorge/prepend.php or
# set 'php_value auto_prepend_file {path-to-PhorgeRoot}/Phorge/prepend.php' 
# in .htaccess  to start automatically
include "../prepend.php";


## display action Welcome
Phorge::displayAction('Welcome');


include "../append.php";
?>
