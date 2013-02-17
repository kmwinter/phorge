<?php

pminclude('lib:Smarty.libs.Smarty');

class PhorgedSmarty extends Smarty {
	
	
	#private static $properties;
	
	private $template;
	
	public function __construct(){
		parent::__construct();
						
		//$this->template_dir = Phorge::getConfigProperty('smarty.template.dir');
        $this->template_dir = '/';
		$this->compile_dir = Phorge::getConfigProperty('smarty.compile.dir');
		$this->config_dir = Phorge::getConfigProperty('smarty.config.dir');
		$this->cache_dir = Phorge::getConfigProperty('smarty.cache.dir');;
		$this->plugins_dir = array(SMARTY_DIR . '/plugins/' , Phorge::getConfigProperty('smarty.plugins.dir'));		
		$this->template = Phorge::getConfigProperty('smarty.template');
		$this->assign('template', $this->template);
		
		$this->force_compile = (Phorge::getConfigProperty('smarty.force.compile') == 'true')? true:false ;
		
		$this->assign('current_date', time());
	}
	
	public function setTemplate($template, $dir = null){
		if($dir){
			$this->template_dir = $dir;			
		}
		$this->template = $template;
	}

    /*
	public function display($path){		
		
		$result = parent::fetch($path);		
		print $result;
	}
	
	
	public function fetch($path){        
		$result = parent::fetch($path);        
		return $result;
	}
	*/
	
}
?>