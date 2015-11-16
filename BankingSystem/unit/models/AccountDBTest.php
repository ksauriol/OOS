<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Account.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\AccountsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class AccountDBTest extends PHPUnit_Framework_TestCase {
	private static $validInput = array(
			"accountID" => 22,
			"profileID" => 18
	);
	
	
	public function testAddAccountWithValidParameter() {
		$account = new Account(AccountDBTest::$validInput);
		
		$this->dbQuery("delete from Accounts where profileID = 18");
		$rowsBeforeAdd = $this->dbSelect("select * from Accounts where profileID = 18");
		$accountID = AccountsDB::addAccount($account);
		$rowsAfterAdd = $this->dbSelect("select * from Accounts where profileID = 18");
		
		$this->assertEmpty($rowsBeforeAdd,
				'It should not have the provided account until the account has been added');
		$this->assertCount(1, $rowsAfterAdd,
				'It should have a new row in the Accounts table of the database when the account parameter is provided');
		$this->assertArrayHasKey('accountID', $rowsAfterAdd[0],
				'It should have a accountID column in the Account table of the database when the Account parameter is provided');
		$this->assertArrayHasKey("profileID", $rowsAfterAdd[0],
				'It should have a profileID column in the Account table of the database when the Account parameter is provided');
		$this->assertTrue(is_numeric($accountID),
				'It should return the profile ID of the added member profile when the profile parameter is provided');
		$this->assertNotEquals(-1, $accountID,
				'It should return a valid profile ID of the added member profile when the profile parameter is provided');
	}
	
	public function testGetAllAccounts() {
		$accounts = AccountsDB::getAllAccounts();
	
		$this->assertNotEmpty($accounts,
				'It should return a non-empty array');
	
		foreach ($accounts as $account) {
			//print_r($Profile->__toString());
			$this->assertInstanceOf('Account', $account,
					'It should return an array of Account objects');
			$this->assertCount(0, $account->getErrors(),
					'It should return an array of Account objects without errors');
			$this->assertEquals(0, $account->getErrorCount(),
					'It should return an array of Account objects with an error count of 0');
		}
	}
	
	public function testGetAccountByWithValidParameters() {
		$account = AccountsDB::getAccountsBy('accountID', 1);
		
		$this->assertInstanceOf('Account', $account[0],
				'It should return a Account object when valid parameters are provided');
		$this->assertEquals(22, $account[0]->getAccountID(),
				'It should return a Account object whose accountID matches the provided input when valid input is provided');
		$this->assertCount(0, $account[0]->getErrors(),
				'It should return a Account object without errors when valid input is provided:' . "\n" . array_shift($account[0]->getErrors()));
		$this->assertEquals(0, $account[0]->getErrorCount(),
				'It should return a Account object with an error count of 0 when valid input is provided');
	}
	private function dbQuery($query) {
		try {
			$db = Database::getDB();
			$stmt = $db->prepare($query);
			$stmt->execute();
	
		} catch (PDOException $e) {
			echo $e->getMessage();
		} catch (RuntimeException $e) {
			echo $e->getMessage();
		}
	}
	
	private function dbSelect($query) {
		try {
			$db = Database::getDB();
			$stmt = $db->prepare($query);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
	
		} catch (PDOException $e) {
			echo $e->getMessage();
		} catch (RuntimeException $e) {
			echo $e->getMessage();
		}
	
		return $rows;
	}
	
	private function checkSession() {
		if (session_status() == PHP_SESSION_NONE)
			session_start();
		if (!isset($_SESSION))
			$_SESSION = array();
		if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
			$_SESSION['dbName'] = 'dhma_testDB';
		if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
			$_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
	}
	
}
?>