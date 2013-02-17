<?php

require 'DefaultPagerLoader.class.php';
class Pager {
	
	protected $request;
	protected $name;
	protected $elements;
	protected $elementsPerPage = 10;
	protected $pageKeyName;
	protected $pageNumbersShown = 6;
	protected $useSession;	
	protected $headerTemplate = 'pagerHeader';
	protected $page;
	protected $headerUrl;
	protected $loader;
	
	public function __construct(Request $request, $name, $useSession = false, $pageKeyName = 'page'){
		$this->loader = new DefaultPagerLoader();
		$this->request = $request;
		$this->elements = new HashMap();
		$this->name = $name;
		#$this->templatePath = $templatePath;		
		$this->useSession = $useSession;
		$this->pageKeyName = $pageKeyName;	

		//determine page
		$this->resolvePage();
		

	}
	
	
	private function resolvePage(){
		$page = null;
		
		if($this->request->containsKey($this->pageKeyName)){
			$page = $this->request->get($this->pageKeyName);	
		}

		
		if($this->useSession){
			$session = Framework::getSession();
			if(empty($page)){	
				if($session->containsKey($this->getSessionKey())){
					$page = $session->get($this->getSessionKey());
				}
				#echo "in session $page";
			}else {
				#echo "put $page in session";
				
				$session->put($this->getSessionKey(), $page);
			}
			
			
		}
			
		/*if($page > $this->numberOfPages()){			
			$page = 1;
			echo 'reset (' . $this->numberOfElements() .')';
		}	*/	
		
		if($page == 0){
			$page = 1;
		}
		
		$this->page = $page;
	}
	
	/*public function setElementLoader(PagerLoader $loader){
		$this->loader = $loader;
	}
	*/
	
	private function getSessionKey(){
		return $this->name . '-' . $this->pageKeyName;
	}
	
	/*public function setTemplatePath($templatePath){
		$this->templatePath = $templatePath;
	}
		
*/	
	public function addElements($elements){
		if(is_array($elements)){
			$this->addElementArray($elements);
		}else if($elements instanceof HashMap){
			$this->addElementHash($elements);
			
		}else {
		
			throw new Exception('Elements added to pager must be in the form of HashMap or Array');
		}
	}

	public function addElementArray($elementArray){
		$this->elements->addArray($elementArray);
	}
	
	public function addElementHash(HashMap $hash){
		$this->elements->combine($hash);
	}
	
	
	public function addElement($element, $key = null){
		if($key == null){
			$this->elements->put(null, $element);
		}else {
			$this->elements->put($key, $element);
		}
		
	}
	
	public function getPageElements(){
		$result = $this->loader->getPageElements($this->elements, $this->getOffset(), $this->getLimit());		
		#return $this->elements->getRange($this->getOffset(), $this->getLimit(), true);
		if($result instanceof HashMap){
			$result = $result->toArray();
		}
		
		if(! is_array($result)){
			throw new Exception('Attempted to return a non-array value in Pager::getPageElements');
		}
				
		return $result;
	}
	
	
	public function getElements(){
		return $this->elements;
	}
	
	
	public function setElementsPerPage($elementsPerPage){
		$this->elementsPerPage = $elementsPerPage;
	}
	
	public function setPageNumbersShown($pns){
		$this->pageNumbersShown = $pns;
	}
	
	public function getPageNumbersShown(){
		return $this->pageNumbersShown;
	}
	
	public function numberOfElements(){
		return $this->loader->getNumberOfElements($this->elements);
		#return $this->elements->count();
	}
	
	public function numberOfPages(){
		return number_format(ceil(($this->numberOfElements() / $this->elementsPerPage)));
	}
	
	public function setPageKeyName($keyName){
		$this->pageKeyName = $keyName;
	}
	
	
	public function getOffset(){		
		return $this->elementsPerPage * ($this->getPage() - 1);	
	}
	
	
	public function getLimit(){
		return $this->elementsPerPage;
	}
	
	public function getPage(){
		return $this->page;
	}
	
	public function getPreviousPage(){
		return $this->getPage() - 1;
	}
	
	public function getNextpage(){
		return $this->getPage() + 1;
	}
	
	public function getHeaderTemplate(){
		return $this->headerTemplate;
	}
	
	public function setHeaderTemplate($template){
		$this->headerTemplate = $template;
	}
	
	public function getAction(){
		return $this->request->get(ACTION);
	}
	
	public function getLocation(){
		
		return $this->request->get('script_name');
	}
	
	
	public function clearSessionPage(){
		$session = Framework::getSession();		
		$session->clearProperty($this->getSessionKey());
		$this->resolvePage();
		
	}
	
	public function getHeaderUrl($page = null){
			
		if($this->headerUrl){
			$url = clone $this->headerUrl;			
		}else {
			#generate default URL object			
			$url = UrlService::getCurrentUrl();
		}
		
		
		if($page == null){
			$page = $this->getPage();
		}
		
		$url->addParam($this->pageKeyName, $page);
		
		return $url;
	}
	
	
	public function setHeaderUrl(Url $url){
		$this->headerUrl = $url;
	}
	
	
	public function getPageNumbers(){
		$pageArray = array();
		$currentPage = $this->getPage();
		$pagesShown = $this->pageNumbersShown;
		
		if($currentPage < $pagesShown){
			$start = 1;
			if($this->numberOfPages() > $pagesShown){
				$end = $pagesShown;	
			}else {
				$end = $this->numberOfPages();
			}
			
		}elseif( $currentPage > ($this->numberOfPages() - $pagesShown)){
			$start = $this->numberOfPages() - $pagesShown;	
			$end = $this->numberOfPages();
			
		}else {
			$start = $currentPage - floor($pagesShown / 2);
			$end = $currentPage + floor($pagesShown / 2);
		}
		for($i = $start; $i <= $end; $i++){
			if($i != $currentPage){
				$pageArray[$i] = $this->getHeaderUrl($i);
			}else {
				$pageArray[$i] = "";
			}
		}
		
		return $pageArray;
	}
	
	public function setPagerLoader(PagerLoader $loader){
		$this->loader = $loader;
	}
}


?>