<?php
class Account extends GenericModelObject {

	private $accountID;
	private $profileID;
	private $SSN;
	

	
	public function __construct($args = null) {
		$this->arguments = $args;
		Messages::reset();
		$this->initialize();
	}
	
	public function getSSN() {
		return $this->SSN;
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
				$this->validateProfileID();
				$this->validateAccountID();
				$this->validateSSN();
		}
	}
	
	private function validateSSN() {
		$this->SSN = $this->extractForm($this->arguments, "SSN");
	}
	
	private function validateAccountID() {
		$this->accountID = $this->extractForm($this->arguments, "accountID");
	}
	
	private function validateProfileID() {
		$this->profileID = $this->extractForm($this->arguments, "profileID");
	}
}
?>