<?php
pminclude('phorge:core.interfaces.Block');
pminclude('phorge:core.DispatcherFactory');
pminclude('phorge:core.ValidationRule');
pminclude('phorge:core.ModelAndView');
pminclude('phorge:core.ModelAndViewName');
pminclude('phorge:exceptions.ValidationException');
pminclude('phorge:core.ValidatorFactory');



abstract class Action {
	
	protected $POSITIVE_RESPONSE = SUCCESS;
	protected $NEGATIVE_RESPONSE = FAILURE;
	protected $hasBeenRun = false;
	protected $autoAuthorize = true;
		
	protected $result;	
	protected $isValid;
	protected $actionName;
	protected $module;
	protected $title;
	
	protected $rules = array();
	protected $errors = array();
		
	protected $allowedRoles = array();
	protected $allowedUsers = array();
	
	
	public function __construct(){		
		#$this->actionName = $actionName;
		#$this->module = $module;		
		$this->init();			
	}
	
	
	/**
	 * init is called from the Action's constructor and is a perfect place
	 * to define the action's validation rules or authorization rules
	 **/
	protected function init(){
		return true;
	}
	
	
	
	
	public function process(Request $request, Response $response){
		
		## run validation rules, determine action's execute method based on result
		$this->isValid = $this->validate($request);
		
		## returned value may be either a ModelAndView object, a ModelAndViewname object, or a Response object
		$returned = null;		
		
		## run user-defined execute method:
		if($this->isValid){
			$this->result = $this->POSITIVE_RESPONSE;
			$returned = $this->doWhenValid($request, $response);
			
		}else {
			$this->result = $this->NEGATIVE_RESPONSE;	
			$returned = $this->doWhenInvalid($request, $response);
			
		}
		
		
		$modelAndView = null;		
		
		## if only response is returned, find default view,  
		if($returned instanceof HashObject){			
			$modelAndView = new ModelAndView($returned, $this->findDefaultView($this->result, $this->actionName, $this->module));	
			
		}else if($returned instanceof ModelAndViewName){
			## ModelAndViewName returned, instanciate View object using the view name and the findDefaultView method
			$modelAndView = new ModelAndView($returned->getResponse(), 
											 $this->findDefaultView($this->result, $returned->getViewName(), $this->module));
			
		}else if($returned instanceof ModelAndView){
			## ModelAndView is returned, pass it along
			$modelAndView = $returned;
		
		}else{
			throw new GeneralException('Invalid return type for action ' . get_class($this));
		}
		
		
		
		## defune ACTION and MODULE values in response
		$response->put(ACTION, $this->actionName);
		$response->put(MODULE, $this->module);
		
		## only run this action once
		$this->hasBeenRun = true;
				
		## set the global page title
		if($this->getTitle()){
			Framework::setPageTitle($this->getTitle());
		}
		
		
		## fin				
		return $modelAndView;
		
	}
	
	
	protected function getView($viewName, $moduleName = null){
		$dispatcher = DispatcherFactory::getDispatcher(ACTION);
		if(!$moduleName){
			$moduleName = $this->module;
		}
		return $dispatcher->makeView($viewName, $moduleName);
	}
	
	
	
	/**
	 * asks Dispatcher to find this action's default view based on validation result, actionName, and ModuleName
	 * 
	 **/
	protected function findDefaultView($result, $actionName, $moduleName = null){
		$dispatcher = DispatcherFactory::getDispatcher(ACTION);
		return $dispatcher->resolveView($result, $actionName, $moduleName);
	}
	
	
	/**
	 * Forwards the request/response to anoter action and returns the response/view from that action
	 * 
	 **/
	protected function forward(Request $request, Response $response, $actionName, $moduleName = null){
		
		$dispatcher = DispatcherFactory::getDispatcher(ACTION);
		$action = $dispatcher->process($actionName, $moduleName);		 
		$modelAndView = $action->process($request, $response);
		## do this so that Framework knows which module to use (since it now could have been forwarded to anywhere)
		return $modelAndView;
	}
	
	
	
	/**
	 * creates validator object from factory, adds it to validator array
	 **/	
	protected function addValidationRule($property, $ruleName, $options = array()){
			
		$this->rules[$property][] = ValidatorFactory::make($ruleName, $property, $options);
		
	}
	
	
	
	
	/**
	 * User Defined execute methods. These will be overridden by child actions.
	 * When valdiation passes, the 'doWhenValid' method is run. The 'doWhenInvalid' is run
	 * otherwise
	*/
	protected function doWhenValid(Request $request, Response $response){
		throw new GeneralException("Attempt to execute generic doWhenValid method in Action ");
	}
	
	protected function doWhenInvalid(Request $request, Response $response){
		throw new GeneralException("Attempt to execute generic doWhenInvalid method in Action ");
	}
	
	
	/**
	 * Internal validation method. This processes the individual validation rules and 
	 * determine if the action result is valid or invalid 
	 *
	 * 
	*/
	protected function validate(Request $request){
				
		$result = true;
				
		foreach($this->rules as $property => $propertyRules){
			foreach($propertyRules as $ruleObject){
					
				try {
					$ruleObject->validate($request);
					
				}catch (ValidationException $ve){
					
					$result = false;
					$this->errors[$property] = $ve->getMessage();
										
				}
			}
		}
		
		return $result;
		
	}
	
	
	
	/**
	 * Checks this action against the AuthManager::authorizeAction() method and 
	 * also checks individual user-defined auth rules
	 * 
	 * 
	*/

	public function authorize(){
	
		if($this->autoAuthorize){			
			#if set to check action auth, will throw exception if not logged in or user doesn't have access						
			if(Framework::getConfigProperty(ACTION_AUTHORIZATION)){													
				AuthManager::authorizeAction($this->actionName, $this->module);
			}
		}
		

		
		if($this->hasRestrictions()){
			## this action has some user-defined restrictions, check to see if authenticated user passes them.
			## if user is not logged in, an AuthenticationException will be thrown, prompting a login  
			
		
			$user = AuthManager::getAuthenticatedUser();
			
			$allowed = false;
			foreach($this->getAllowedRoles() as $roleId){
				if($user->hasRole($roleId)){
					#$allowed = true;
					#break;
					return true;
				}
			}
			
			
			
			if(! $allowed){
				foreach($this->getAllowedUsers() as $uniqueId){
					if($user->getUniqueId() === $uniqueId){
						#$allowed = true;
						#break;
						return true;
					}
				}
			}

			## if the user's credentials haven't matched any of the restrictions by now, raise the alarm
			throw new AuthorizationException($user->getUniqueId(), "Authenticated user does not have access to this page");
			

		}else {
			## this action has no restrictions, authorization automatically passes		
			return true;
		}
	
	
	
	
	}
	
	
	
	public function getActionName(){
		return $this->actionName;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	protected function setTitle($title){
		$this->title = $title;
	}
	
	protected function getErrors(){
		return $this->errors;
	}
	
	public function getAllowedRoles(){
		return $this->allowedRoles;
	}
	
	public function getAllowedUsers(){
		return $this->allowedUsers;
	}
	
	public function hasRestrictions(){
		return ( count($this->allowedUsers) + count($this->allowedRoles) ) > 0;
	}
	
	protected function allowUser($uniqueId){
		$this->allowedUsers[] = $uniqueId;
	}
	
	protected function allowRole($roleId){
		$this->allowedRoles[] = $roleId;
	}
	
	
	
}

?>