<?php
global $frameworkRoot;
$frameworkRoot = '/www/Phorge';
include "$frameworkRoot/prepend.php";

Phorge::displayBlock('HelloWorld', 'HelloWorld');


?>

