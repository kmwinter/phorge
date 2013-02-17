<?php

pminclude('sfp:phorge.SmartyActionDispatcher');
class SmartySubActionDispatcher extends SmartyActionDispatcher {}

#pminclude('sfp:smarty.PhorgedSmarty');
#pminclude('phorge:core.SubActionDispatcher');

/*class SmartySubActionDispatcher extends SubActionDispatcher {

	public function process($actionName, $moduleName = null){
		$request = Phorge::getRequest();
		$viewContent = '';
		try {
			#retrieve rendered view content 			
			
			return parent::process($actionName, $moduleName);
			
		}catch (Exception $e){
			
			throw $e;
			$dispatcher = DispatcherFactory::getDispatcher(EXCEPTION);			
			$viewContent = $dispatcher->process($e, $request);
			
			
				$smarty = new PhorgedSmarty();
						
				$smarty->assign(MODULE, $request->get(MODULE));
				$smarty->assign(ACTION, $request->get(ACTION));
				
				
				# assign rendered view content to template 
				$smarty->assign('content', $viewContent);
				
				# return rendered template
				return $smarty->fetch('layout.tpl');		
			
			
		}
		
		
		
		
	}


}*/


?>