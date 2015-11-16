<?php
class AccountInfo extends GenericModelObject {
	private $accountID;
	private $SSN;
	private $firstName;
	private $middleName;
	private $lastName;
	private $address;
	private $tel;
	const MAX_NAME = 50;
	public function __construct($args = null) {
		$this->arguments = $args;
		Messages::reset();
		$this->initialize();
	}
	
	public function getSSN() {
		return $this->SSN;
	}
	
	public function getFirstName() {
		return $this->firstName;
	}
	
	public function getMiddleName() {
		return $this->middleName;
	}
	
	public function getLastName() {
		return $this->lastName;
	}
	
	public function getAccountID() {
		return $this->accountID;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function getTel() {
		return $this->tel;
	}
	
	public function getParameters() {
		$paramArray = array(
				"profileID" => $this->profileID,
				"firstName" => $this->firstName,
				"middleName" => $this->middleName,
				"lastName" => $this->lastName,
				"address" => $this->address,
				"tel" => $this->tel,
				"SSN"		=> $this->SSN
		);
		return $paramArray;
	}
	
	public function __toString() {
		$str =
		"Account ID: [" . $this->accountID . "]\n" .
		"First Name: [" . $this->firstName . "]\n" .
		"Middle Name: [" . $this->middleName . "]\n" .
		"Last Name: [" . $this->lastName . "]\n" .
		"Address: [" . $this->address . "]\n" .
		"Telephone: [" . $this->tel . "]\n" .
		"SSN : [" .		  $this->SSN 	   . "]\n";
		return $str;
	}
	
	protected function initialize() {
		$this->errorCount = 0;
		$this->errors = array();
	
		if (is_null($this->arguments)) {
			$this->profileID = 1;
			$this->profileID = 1;
			$this->SSN = 0;
		} else {
			$this->validateAccountID();
			$this->validateFirstName();
			$this->validateMiddleName();
			$this->validateLastName();
			$this->validateAddress();
			$this->validateTel();
			$this->validateSSN();
		}
	}
	
	private function validateFirstName() {
		$this->firstName = $this->extractForm($this->arguments, "firstName");//self::INDEX_FIRST_NAME);
		if (empty($this->firstName)) {
			return;
		}
	
		if (strlen($this->firstName) > self::MAX_NAME) {
			$this->setError("firstName", "FIRST_NAME_TOO_LONG");
			return;
		}
	
		$options = array("options" => array("regexp" => "/[a-zA-Z ]/"));
		if (!filter_var($this->firstName, FILTER_VALIDATE_REGEXP, $options)) {
			$this->setError("firstName", "FIRST_NAME_HAS_INVALID_CHARS");
			return;
		}
	}
	
	private function validateMiddleName() {
		$this->middleName = $this->extractForm($this->arguments, "middleName");//self::INDEX_FIRST_NAME);
		if (empty($this->middleName)) {
			return;
		}
	
		if (strlen($this->middleName) > self::MAX_NAME) {
			$this->setError("middleName", "MIDDLE_NAME_TOO_LONG");
			return;
		}
	
		$options = array("options" => array("regexp" => "/[a-zA-Z ]/"));
		if (!filter_var($this->middleName, FILTER_VALIDATE_REGEXP, $options)) {
			$this->setError("middleName", "MIDDLE_NAME_HAS_INVALID_CHARS");
			return;
		}
	}
	
	private function validateLastName() {
		$this->lastName = $this->extractForm($this->arguments, "lastName");//self::INDEX_FIRST_NAME);
		if (empty($this->lastName)) {
			return;
		}
	
		if (strlen($this->lastName) > self::MAX_NAME) {
			$this->setError("lastName", "LAST_NAME_TOO_LONG");
			return;
		}
	
		$options = array("options" => array("regexp" => "/[a-zA-Z ]/"));
		if (!filter_var($this->lastName, FILTER_VALIDATE_REGEXP, $options)) {
			$this->setError("lastName", "LAST_NAME_HAS_INVALID_CHARS");
			return;
		}
	}
	
	private function validateAccountID() {
		$this->accountID = $this->extractForm($this->arguments, "accountID");
	}
	
	private function validateSSN() {
		$this->SSN = $this->extractForm($this->arguments, "SSN");
	}
	
	private function validateAddress() {
		$this->address = $this->extractForm($this->arguments, "address");
	}
	
	private function validateTel() {
		$this->tel = $this->extractForm($this->arguments, "tel");
	}
}
?>