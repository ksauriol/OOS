<?php
class Account extends GenericModelObject {

	private $bankID;
	private $firstName;
	private $lastName;
	private $SSN;
	private $address;
	private $balance;
	
	public function __construct($args = null) {
		$this->arguments = $args;
		Messages::reset();
		$this->initialize();
	}
	
	public function getSSN() {
		return $this->SSN;
	}

	
	public function getAccountID() {
		return $this->accountID;
	}
	
	public function getFirstName() {
	    return $this->firstName;
	}
	
	public function getLastName() {
	    return $this->lastName;
	}
	
	public function getAddress() {
	    return $this->address;
	}
	
	public function getBalance() {
	    return $this->balance;
	}
	
	public function setProfileID($input){
		$this->arguments['profileID']=$input;
		$this->validateProfileID();
	}
	
	public function getParameters() {
		$paramArray = array(
    		"profileID" => $this->profileID,
    		"accountID" => $this->accountID,
    		"SSN"		=> $this->SSN
		);
		return $paramArray;
	}
	
	public function __toString() {
		$str =
		"Account ID: [" . $this->accountID . "]\n" .
		"Profile ID: [" . $this->profileID . "]\n" .
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
			$this->validateLastName();
			$this->validateSSN();
			$this->validateAddress();
			$this->validateBalance();
		}
	}
	
	private function validateSSN() {
		$this->SSN = $this->extractForm($this->arguments, "SSN");
	}
	
	private function validateAccountID() {
		$this->accountID = $this->extractForm($this->arguments, "bankID");
	}
	
	private function validateFirstName() {
	    $this->firstName = $this->extractForm($this->arguments, "firstName");
	}
	
	private function validateLastName() {
	    $this->lastName = $this->extractForm($this->arguments, "lastName");
	}
	
	private function validateAddress() {
	    $this->address = $this->extractForm($this->arguments, "address");
	}
	
	private function validateBalance() {
	    $this->balance = $this->extractForm($this->arguments, "balance");
	}
}
?>