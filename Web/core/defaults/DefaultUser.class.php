<?php

pminclude('phorge:core.interfaces.PhorgeUser');
class DefaultUser implements PhorgeUser {
	
	private $uniqueId;
	private $roles;
	private $name;
	
	
	public function __construct($uniqueId, $roles = array(), $name = 'No Name'){		
		$this->uniqueId = $uniqueId;
		$this->roles  = $roles;
		$this->name = $name;
	}
	
	public function getUniqueId(){
		return $this->uniqueId;
	}
	public function setUniqueId($uniqueId){
		$this->uniqueId = $uniqueId;
	}
	
	public function getRoles(){
		return $this->roles;
	}
	
	public function setRoles($roles){
		if(! is_array($roles)){
			throw new GeneralException('Invalid roles array');
		}
		$this->roles = $roles;
	}
	

	public function addRole($roleId){
		$this->roles[] = $roleId;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function hasRole($roleId){
		return in_array($roleId, $this->roles);
	}
	
	
}

?>