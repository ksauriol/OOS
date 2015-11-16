<?php
class AccountInfoDB {

	// adds the specified Profile object to the database
	public static function addAccount($account) {
		$returnAccountID = -1;

		try {
			$db = Database::getDB();
			$stmt = $db->prepare(
					"insert into AccountInfo(accountID, firstName, middleName, lastName, address, tel, SSN)
					values (:accountID, :firstName, :middleName, :lastName, :address, :tel, :SSN)"
			);
			$stmt->execute(array(
					"accountID" => $account->getAccountID(),
					"firstName" => $account->getFirstName(),
					"middleName" => $account->getMiddleName(),
					"lastName" => $account->getLastName(),
					"address" => $account->getAddress(),
					"tel" => $account->getTel(),
					"SSN"=> $account->getSSN()
			));
			 
			$returnAccountID = $db->lastInsertId("accountID");
			 
		} catch (PDOException $e) {
			$account->setError("accountID", "ADD_ACCOUNT_INFO_FAILED");
		} catch (RuntimeException $e) {
			$account->setError("database", "DB_CONFIG_NOT_FOUND");
		}
		 
		return $returnAccountID;
	}
	 
	/*
	public static function addAccountNoOwner($account) {
		$returnAccountID = -1;
		 
		try {
			$db = Database::getDB();
			$stmt = $db->prepare(
					"insert into Accounts(accountID, SSN)
					values (:accountID, :SSN)"
			);
			//print_r( $account->getSSN().'+<br>');
			$stmt->execute(array(
					"accountID" => $account->getAccountID(),
					"SSN"		=> $account->getSSN()
			));

			$returnAccountID = $db->lastInsertId("accountID");

		} catch (PDOException $e) {
			$account->setError("accountID", "ADD_ACCOUNT_FAILED");
		} catch (RuntimeException $e) {
			$account->setError("database", "DB_CONFIG_NOT_FOUND");
		}
		 
		return $returnAccountID;
	}*/
	 
	public static function getAllAccounts() {
		$allAccounts = array();
		 
		try {
			$db = Database::getDB();
			$stmt = $db->prepare(
					"select * from AccountInfo"
					//	profileID, firstName, middleName, lastName, email, phone, gender, address, dob, profileType
			);
			$stmt->execute();
			 
			foreach ($stmt as $row) {
				$account = new AccountInfo($row);
				//print_r($account->__toString());
				if (!is_object($account) || $account->getErrorCount()!=0)
					throw new PDOException("Failed to create valid AccountInfo from getAllAccounts");
				 
				$allAccounts[] = $account;
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		} catch (RuntimeException $e) {
			echo $e->getMessage();
		}
		 
		return $allAccounts;
	}

	public static function editAccount($account) {
		try {
			$db = Database::getDB ();
			if (is_null($account) || $account->getErrorCount() > 0)
				return $account;
			$checkAccount = AccountInfoDB::getAccountsBy('accountID', $account->getAccountID());
			if (empty($checkAccount))
				$account->setError('accountId', 'ACCOUNT_DOES_NOT_EXIST');
			if ($account->getErrorCount() > 0)
				return $account;
			//print_r($account->getAccountID().'<br>'.$account->getProfileID().'<br>');
			$query = "UPDATE Accounts SET firstName = :firstName, middleName = :middleName, lastName = :lastName, 
									address= :address, tel= :tel
	    			                 WHERE accountID = :accountID";

			$statement = $db->prepare ($query);
			$statement->bindValue(":firstName", $account->getFirstName());
			$statement->bindValue(":middleName", $account->getMiddleName());
			$statement->bindValue(":lastName", $account->getLastName());
			$statement->bindValue(":address", $account->getAddress());
			$statement->bindValue(":tel", $account->getTel());
			$statement->bindValue(":accountID", $account->getAccountID());
			$statement->execute ();
			$statement->closeCursor();
		} catch (Exception $e) { // Not permanent error handling
			$account->setError('accountID', 'ACCOUNT_INFO_COULD_NOT_BE_UPDATED');
		}
		return $account;
	}
	 
	public static function getAccountsBy($type, $value) {
		$allowed = ['accountID','SSN'];
		$allAccounts = array();

		try {
			if (!in_array($type, $allowed))
				throw new PDOException("$type not allowed search criterion for AccountInfo");

			$db = Database::getDB();
			$stmt = $db->prepare(
					"select *
					from Accounts
					where ($type = :$type)");
			$stmt->execute(array(":$type" => $value));

			foreach ($stmt as $row) {
				$account = new Account($row);
				//print_r($account->__toString());
				if (!is_object($account) || $account->getErrorCount()!=0)
					throw new PDOException("Failed to create valid AccountInfo from getAllAccounts");
				 
				$allAccounts[] = $account;
			}

		} catch (PDOException $e) {
			echo $e->getMessage();
		} catch (RuntimeException $e) {
			echo $e->getMessage();
		}

		return $allAccounts;
	}
	public static function deleteAccountsBy($type, $value) {
		$allowed = ['accountID'];
		$account = null;
		$success = true;

		try {
			if (!in_array($type, $allowed))
				throw new PDOException("$type not allowed search criterion for AccountInfo");

			$db = Database::getDB();
			$stmt = $db->prepare(
					"select *
					from Accounts
					where ($type = :$type)"
			);
			$stmt->execute(array(":$type" => $value));

			if(count($stmt) > 1)
				throw new PDOException("Error: multiple results returned");

			$stmt = $db->prepare(
					"delete from Accounts
					where ($type = :$type)"
			);
			$stmt->execute(array(":$type" => $value));

		} catch (PDOException $e) {
			echo $e->getMessage();
			$success = false;
		} catch (RuntimeException $e) {
			echo $e->getMessage();
			$success = false;
		}

		return $success;
	}
}
?>