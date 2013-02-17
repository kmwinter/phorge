<?php
pminclude('phorge:core.Phorge');
$response = Phorge::getResponse();
?>
<html>
<head><title>Example Form</title></head>
<body>
<h2><?php echo $response->get('message')?></h2>

</body>
</html>
