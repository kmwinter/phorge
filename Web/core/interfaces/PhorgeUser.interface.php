<?php
interface PhorgeUser {
	
	public function getUniqueId();
	public function getRoles();
	public function getName();
	public function hasRole($roleId);
	
}

?>