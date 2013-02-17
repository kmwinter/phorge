<?php

pminclude('sfp:pager.Pager');

class DbPager extends Pager {
	/*
	private $dbLoader;
	protected $elements; 
	private $numberOfElements;
	
	
	public function __construct(PagerLoader $loader, Request $request, $name, $useSession = false, $pageKeyName = 'page'){
		parent::__construct($request, $name, $useSession, $pageKeyName);
		$this->elements = null;
		$this->dbLoader = $loader;
		$this->numberOfElements = -1;
	}
	
	
	public function getPageElements(){
		if($this->elements === null){
			$this->elements = $this->dbLoader->pagerLoad($this->getOffset(), $this->getLimit());	
		}
		
		return $this->elements;
	}
	
	public function numberOfElements(){
		
		if($this->numberOfElements === -1){	
			#$this->elements = $this->dbLoader->pagerLoad($this->getOffset(), $this->getLimit());
			#$this->numberOfElements = count($this->elements);				
			$this->numberOfElements = $this->dbLoader->pagerNumberOfElements();
		}
		
		return $this->numberOfElements;
		
	}
	*/
	
	
}


?>