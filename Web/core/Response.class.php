<?php

pminclude('phorge:core.ValidatorErrors');
class Response extends HashMap {
	
    private $actionName;
    private $moduleName;
    private $errors;


    public function __construct(){
        #$this->actionName = $actionName;
        #$this->moduleName = $moduleName;
        $this->errors = new ValidatorErrors();
    }

	public function setValue($key, $value){
		parent::put($key, $value);
	}
	
	public function getValue($key){
		return parent::get($key);
	}
	
	
        public function getActionName() {
            return $this->actionName;
        }

        public function setActionName($actionName) {
            $this->actionName = $actionName;
        }

        public function getModuleName() {
            return $this->moduleName;
        }

        public function setModuleName($moduleName) {
            $this->moduleName = $moduleName;
        }



        public function getErrors() {
            return $this->errors;
        }

        public function setErrors(ValidatorErrors $errors) {
            $this->errors = $errors;
        }


	
}


?>