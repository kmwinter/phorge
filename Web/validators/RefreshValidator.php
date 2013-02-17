<?php
pminclude('phorge:core.interfaces.ValidationRule');
class RefreshValidator implements ValidationRule {
	
	public function validate(Request $request, $property, $options){
		
		if(key_exists('allow', $options)){
			$allow = $options['allow'];	
			if($allow == 'false'){
				$allow = false;
			}
		}else {
			$allow = false;
		}		
		
		
		
		if($request->isRepost()){
			if(!$allow){
				return Validator::resolveMessage('Identical Post', $options);
			}
		}
		
		
	}
}