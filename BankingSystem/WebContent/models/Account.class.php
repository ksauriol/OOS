<?php
class Account extends GenericModelObject {

	private $accountID;
	private $profileID;
	private $SSN;
	private $entry;
	

	
	public function __construct($args = null) {
		$this->arguments = $args;
		Messages::reset();
		$this->initialize();
	}
	
	public function getSSN() {
		return $this->SSN;
	}
	
	public function getEntry() {
		return $this->entry;
	}
	
	public function getProfileID() {
		return $this->profileID;
	}
	
	public function getAccountID() {
		return $this->accountID;
	}
	
	public function setProfileID($input){
		$this->arguments['profileID']=$input;
		$this->validateProfileID();
	}
	
	public function getParameters() {
		$paramArray = array(
				"profileID" => $this->profileID,
				"accountID" => $this->accountID,
				"SSN"		=> $this->SSN,
				"entry"		=> $this->entry
				);
				return $paramArray;
	}
	
	public function __toString() {
		$str =
		"Account ID: [" . $this->accountID . "]\n" .
		"Profile ID: [" . $this->profileID . "]\n" .
		"SSN : [" .		  $this->SSN 	   . "]\n".
		"Entry : [" .      $this->entry 	   . "]\n";
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
				$this->validateProfileID();
				$this->validateAccountID();
				$this->validateSSN();
				$this->validateEntry();
		}
	}
	
	private function validateSSN() {
		$this->SSN = $this->extractForm($this->arguments, "SSN");
	}
	
	private function validateEntry() {
		$this->entry = $this->extractForm($this->arguments, "entry");
	}
	
	private function validateAccountID() {
		$this->accountID = $this->extractForm($this->arguments, "accountID");
	}
	
	private function validateProfileID() {
		$this->profileID = $this->extractForm($this->arguments, "profileID");
	}
}
?>