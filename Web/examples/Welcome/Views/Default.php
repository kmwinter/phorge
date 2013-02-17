<?php
pminclude('phorge:core.Phorge');
pminclude('phorge:core.UrlService');
pminclude('phorge:core.Url');
pminclude('phorge:core.ActionUrl');
$response = Phorge::getResponse();

?>
<html>
<head>
	<title>Phorge</title>
</head>
	<body>
		<?php Phorge::displayBlock('PageHeader');?>
		<br>
		<h4>Welcome to Phorge</h4> 
		<?php echo $response->get("message") ?>
		<br><br>
		You can find the code for the examples below in the Phorge/Modules directory.
		<br><br>
		<a href="<?php echo new ActionUrl('Default', 'HelloWorld') ?>">Hello World example</a>
		<br>
		<a href="<?php echo new Url('HelloWorldBlock.php') ?>">Hello World Block example</a>
		<br>
		<a href="<?php echo new ActionUrl ('FormExample', 'Welcome')?>">Form Example</a>
				
				
		<?php Phorge::displayBlock('Log');?>		
	</body>
</html>
