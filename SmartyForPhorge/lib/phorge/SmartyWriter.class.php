<?php

pminclude('phorge:core.interfaces.PageWriter');
pminclude('sfp:smarty.PhorgedSmarty');

class SmartyWriter implements PageWriter {

    public function writeAction($viewContent) {        
        
        
        $templateFile = Phorge::getConfigProperty('smarty.template.file');
        if(! file_exists($templateFile)){
            throw new Exception("Main site template file $templateFile does not exist");
        }

        
       	$request = Phorge::getRequest();
		$s = new PhorgedSmarty();
		#$title = Phorge::getPageTitle();
		#$s->assign('page_title_main', $title);
		
		$s->assign(MODULE, $request->get(MODULE));
		$s->assign(ACTION, $request->get(ACTION));
		
		
		# assign rendered view content to template 
        #echo "=$count=$viewContent=$count=";
		#$s->assign('action_content', $viewContent);
        $s->assign('action_content', $viewContent);
		
		 #return rendered template
		
		$s->display($templateFile);
        return null;
				
	
	}
	
	
	public function writeBlock($viewContent){
		#return $this->write($viewResults);
		#return $this->write($viewContent);
        print $viewContent;
	}
	
	
	
	public function writeError($viewContent){
        
		$request = Framework::getRequest();
		
		$smarty = new PhorgedSmarty();
		$title = Phorge::getPageTitle();
		$smarty->assign('page_title_main', $title);		
		
		
		$smarty->assign(MODULE, $request->get(MODULE));
		$smarty->assign(ACTION, $request->get(ACTION));
		
		
		# assign rendered view content to template 
		$smarty->assign('content', $viewContent);
        $templateFile = Phorge::getConfigProperty('sfp.template.file');   
		$smarty->display($templateFile);

		return null;
	}
	
	
}

?>