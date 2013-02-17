<?php
pminclude('phorge:core.interfaces.ValidationRule');
pminclude('phorge:core.Validator');
class ExternalLinkValidator implements ValidationRule {
	
	public function validate(Request $request, $property, $options){
		
		$value =  $request->get($property);
		

		if(! empty($value)) {
			$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
			
			if(!eregi($urlregex, $value) ) {
           		return Validator::resolveMessage(Validator::LABEL_STRING . ' is not a valid External Link', $options);
			}
		}
		
		
	}
}


?>