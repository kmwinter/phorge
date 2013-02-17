<?php

pminclude('phorge:validators.*');
class ValidatorTests extends UnitTestCase {

	
	
	public function testExists(){
		$key = 'test_exists';
		
		$request = Phorge::getRequest();
		$response = new Response();
		
		$request->put($key, 'something something');
		$errors = new ValidatorErrors();		
		$error = Validator::validateProperty($key, 'Exists', array(LABEL=>'My Key'));		
		$this->assertEqual($error, null);
		
		$request->put($key, null);
		$error = Validator::validateProperty($key, 'Exists', array(LABEL=>'My Key'));		
		$this->assertTrue(is_array($error));
		
	}
   
}
?>