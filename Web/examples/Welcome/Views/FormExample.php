<?php
	pminclude('phorge:core.Phorge');
	$response = Phorge::getResponse();
	
	$actionForm = $response->get('form');
	
	foreach ($actionForm->getFormErrors() as $error){
		echo '<div style="color:red;">' . $error . '</div>';
	}
?>
<html>
<head><title>Example Form</title></head>
<body>
<div>

	<form action="<?php echo $actionForm->getLocation()?>" method="POST">
	<input type="hidden" name="<?php echo ACTION?>" value="<?php echo $actionForm->getAction()?>">


	<table border="0" cellpadding="5">
	<tr>	
		<td>Non required field</td>
		<td><input name="not_required" value="<?php echo $actionForm->getValue('not_required')?>"></td>
	</tr>	
	<tr>	
		<td>Required field</td>
		<td><input name="required" value="<?php echo $actionForm->getValue('required')?>"></td>
	</tr>	
	<tr>	
		<td>Numeric field</td>
		<td><input name="numeric" value="<?php echo $actionForm->getValue('numeric')?>"></td>
	</tr>	
	<tr>	
		<td>Email Address:</td>
		<td><input name="email" value="<?php echo $actionForm->getValue('email')?>"></td>
	</tr>	
	<tr>	
		<td>Url:</td>
		<td><input name="url" value="<?php echo $actionForm->getValue('url')?>"></td>
	</tr>	
	<tr>	
		<td>One of these is required:</td>		
		<td>
			<?php $selected = $actionForm->propertyHasValue('one','one') ? "checked":''?>
			<input type="checkbox" name="one" value="one" <?php echo $selected?>> One <br>
			<?php $selected = $actionForm->propertyHasValue('two', 'two') ? "checked":''?>
			<input type="checkbox" name="two" value="two" <?php echo $selected?>> two <br>
			<?php $selected = $actionForm->propertyHasValue('three','three') ? "checked":''?>
			<input type="checkbox" name="three" value="three" <?php echo $selected?>> three <br>		
		</td>
	</tr>	
	<tr>	
		<td>Do you accept?</td>		
		<td>
			<?php $selected = $actionForm->getValue('approve') == 'yes' ? "checked":''?>
			<input type="radio" name="approve" value="yes" <?php echo $selected?>> Yes, I accept <br>
			<?php $selected = $actionForm->getValue('approve') == 'no' ? "checked":''?>
			<input type="radio" name="approve" value="no" <?php echo $selected?>> No, I don't! <br>
			
		</td>
	</tr>	
	<tr>
		 <td colspan="2" align="center">
			<input type="submit" value="Submit"/>		 	
		 </td>
	</tr>
	</table>
	
	</form>
</div>
</body>
</html>