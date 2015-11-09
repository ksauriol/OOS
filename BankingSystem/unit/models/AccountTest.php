<?php

require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Account.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class AccountTest extends PHPUnit_Framework_TestCase {

	private static $validInput = array(
					"accountID" => 22,
					"profileID" => 7
			);
	private static $validInput2 = array(
			"accountID" => 7
			);
	
	public function testCreateValidAccount() {
		$validAccount = new Account(AccountTest::$validInput);
		$validAccount2 = new Account(AccountTest::$validInput2);
		//   print_r($validProfile->__toString());
		$this->assertInstanceOf('Account', $validAccount,
				'It should create a Account object when valid input is provided');
		$this->assertEquals(0, $validAccount->getErrorCount(),
				'It should not have errors when valid input is provided');
		$this->assertInstanceOf('Account', $validAccount2,
				'It should create a Profile object when valid input is provided');
		$this->assertEquals(0, $validAccount2->getErrorCount(),
				'It should not have errors when valid input is provided');
	}
	
	public function testParameterExtraction() {
		$validAccount = new Account(AccountTest::$validInput);
		$validAccount2 = new Account(AccountTest::$validInput2);
		$params = $validAccount->getParameters();
		$params2 = $validAccount2->getParameters();
		
		$this->assertArrayHasKey('profileID', $params,
				'It should return a parameter array with "profileID" as a key');
		$this->assertArrayHasKey('accountID', $params,
				'It should return a parameter array with "accountID" as a key');
		$this->assertArrayHasKey('profileID', $params,
				'It should return a parameter array with "profileID" as a key');
		$this->assertArrayHasKey('accountID', $params,
				'It should return a parameter array with "accountID" as a key');
		
		$this->assertEquals(AccountTest::$validInput['profileID'], $params['profileID'],
				'It should return a parameter array with a value for key "profileID" that matches the provided input');
		$this->assertEquals(AccountTest::$validInput['accountID'], $params['accountID'],
				'It should return a parameter array with a value for key "accountID" that matches the provided input');
		$this->assertEquals(null, $params2['profileID'],  //testing it with null for those acc without owners
				'It should return a parameter array with a value for key "profileID" that matches the provided input');
		$this->assertEquals(AccountTest::$validInput2['accountID'], $params2['accountID'],
				'It should return a parameter array with a value for key "accountID" that matches the provided input');
	}
	
	public function testAccessorMethods() {
		$validAccount = new Account(AccountTest::$validInput);
		$validAccount2 = new Account(AccountTest::$validInput2);
		
		$this->assertEquals(AccountTest::$validInput['profileID'],$validAccount->getProfileID(),
				'It should return a value for field "profileID" that matches the provided input');
		$this->assertEquals(null,$validAccount2->getProfileID(),
				'It should return a value for field "profileID" that matches the provided input');
		$this->assertEquals(AccountTest::$validInput['accountID'],$validAccount->getAccountID(),
				'It should return a value for field "accountID" that matches the provided input');
		$this->assertEquals(AccountTest::$validInput2['accountID'],$validAccount2->getAccountID(),
				'It should return a value for field "accountID" that matches the provided input');
	}
}
?>