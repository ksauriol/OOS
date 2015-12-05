<?php
class Profile extends GenericModelObject implements JsonSerializable {
    
    private $arguments;
    private $profileID;
    private $email;
    private $phone;
    private $gender;
    private $dob;
    private $address;
    private $SSN;
    private $passwordChanged;
    private $password;
    private $isLoggedIn;
    private $accountID;
    private $dateCreated;
    private $isEmployee;
    
    //private static $allowedProfileTypes = array('member', 'employee');
    
    const MAX_NAME = 50;
    const MAX_EMAIL = 50;
    const MAX_PHONE = 15;
    
    public function __construct($args = null) {
        $this->arguments = $args;
        Messages::reset();
        $this->initialize();
    }
    
    public function getTemp() {
    	return $this->temp;
    }
    
    public function getPassword() {
    	return $this->password;
    }
    
    public function setPasswordChanged() {
    	$this->passwordChanged=true;
    }
    
    public function getPasswordChanged() {
    	return $this->passwordChanged;
    }
    
    public function setPassword($input) {
    	$this->arguments['password']=$input;
		$this->validatePassword();
    }
    
    public function setProfileID($input) {
        $this->arguments['profileID'] = $input;
        $this->validateProfileID();
    }
    
    public function getProfileID() {
        return $this->profileID;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function getPhoneNumber() {
        return $this->phone;
    }
    
    public function getGender() {
        return $this->gender;
    }
    
    public function getDOB() {
        return $this->dob;
    }
    
    public function getSSN() {
    	return $this->SSN;
    }
    
    public function getAddress() {
        return $this->address;
    }
    
    public function getIsLoggedIn() {
    	return $this->isLoggedIn;
    }
    
    public function getIsEmployee() {
    	return $this->isEmployee;
    }
    
    public function getAccountID() {
        return $this->accountID;
    }
    
    public function getDateCreated() {
        return $this->dateCreated;
    }
    
    // Returns data fields as an associative array
    public function getParameters() {
        $paramArray = array(
            "profileID" => $this->profileID,
            "email" => $this->email,
            "phone" => $this->phone,
            "gender" => $this->gender,
            "address" => $this->address,
            "dob" => $this->dob,
        	"password" => $this->password,
            "isLoggedIn" => $this->isLoggedIn,
            "acccountID" => $this->accountID,
            "dateCreated" => $this->dateCreated,
        	"passwordChanged" => $this->passwordChanged
        );
        
        return $paramArray;
    }
    
    public function isLoggedIn() {
        return $this->isLoggedIn;
    }
    
    public function setLoggedIn($boolean) {
        $this->isLoggedIn = $boolean;
    }
    
    public function isEmployee() {
        return $this->isEmployee;
    }
    
    public function setEmployee($boolean) {
        $this->isEmployee = $boolean;
    }
    
    public function isPasswordChanged() {
        return $this->passwordChanged;
    }
    
    public function __toString() {
        $str =
            "Profile ID: [" . $this->profileID . "]\n" .
            "E-mail address: [" . $this->email . "]\n" .
            "Phone number: [" . $this->phone . "]\n" .
            "Gender: [" . $this->gender . "]\n" .
            "Address: [" . $this->address . "]\n" .
            "Date of birth: [" . $this->dob . "]\n".
            "password: [" . $this->password . "]\n".
            "Is Logged In: [" . $this->isLoggedIn . "]\n" .
            "Account ID: [" . $this->accountID . "]\n" .
            "Date Created: [" . $this->dateCreated . "]" .
            "Is Employee: [" . $this->isEmployee . "]" .
            "passwordChanged: [" . $this->passwordChanged . "]";
        
        return $str;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->arguments)) {
            $this->profileID = "";
            $this->email = "";
            $this->phone = "";
            $this->gender = "";
            $this->address = "";
            $this->dob = "";
            $this->isLoggedIn = false;
            $this->accountID = "";
            $this->dateCreated = "";
            $this->isEmployee = false;
            $this->passwordChanged=false;
        }
        else {
            $this->validateProfileID();
            $this->validateEmail();
            $this->validatePhone();
            $this->validateGender();
            $this->validateAddress();
            $this->validateDOB();
            $this->validateSSN();
            $this->validatePassword();
            $this->validateIsLoggedIn();
            $this->validateAccountID();
            $this->validateDateCreated();
            $this->validateIsEmployee();
            $this->validatePasswordChanged();
        }
    }
    
    private function validateTemp() {
    	$this->temp = $this->extractForm($this->arguments, "temp");
    }
    
    private function validatePassword() {
    	$this->password = $this->extractForm($this->arguments, "password");
    }
    
    private function validatePasswordChanged() {
    	$this->passwordChanged = $this->extractForm($this->arguments, "passwordChanged");
    }
    
    private function validateSSN() {
    	$this->SSN = $this->extractForm($this->arguments, "SSN");
    }
    
    private function validateProfileID() {
        $this->profileID = $this->extractForm($this->arguments, "profileID");
    }
    
    private function validateEmail() {
        $this->email = $this->extractForm($this->arguments, "email");
        if (empty($this->email)) {
            return;
        }
        
        if (strlen($this->email) > self::MAX_EMAIL) {
            $this->setError("email", "EMAIL_TOO_LONG");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/"));
        if (!filter_var($this->email, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("email", "EMAIL_INVALID");
            return;
        }
    }
    
    private function validatePhone() {
        $this->phone = $this->extractForm($this->arguments, "phone");
        if (empty($this->phone)) {
            return;
        }
        
    /*   if (strlen($this->phone) > self::MAX_PHONE) {
            $this->setError("phone", "PHONE_TOO_LONG");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$/"));
        if (!filter_var($this->phone, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("phone", "PHONE_INVALID");
            return;
        }*/
    }
    
    private function validateGender() {
        $this->gender = $this->extractForm($this->arguments, "gender");
        if (empty($this->gender)) {
            return;
        }
        
      /*  $options = array("options" => array("regexp" => "/^(male|female)$/i"));
        if (!filter_var($this->gender, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("gender", "GENDER_INVALID");
            return;
        }*/
    }
    
    private function validateAddress() {
        $this->address = $this->extractForm($this->arguments, "address");
    }
    
    private function validateDOB() {
        $this->dob = $this->extractForm($this->arguments, "dob");
        if (empty($this->dob)) {
            return;
        }
        
        /*$options = array("options" => array("regexp" => "/^((\d{4}[\/-]\d\d[\/-]\d\d)|(\d\d[\/-]\d\d[\/-]\d{4}))$/"));
        if (!filter_var($this->dob, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("dob", "DOB_INVALID");
            return;
        }*/
    }
    
    private function validateAccountID() {
        $this->accountID = $this->extractForm($this->arguments, "bankID");
        if (empty($this->accountID)) {
            $this->accountID = $this->extractForm($this->arguments, "accountID");
            if (empty($this->accountID)) {
                $this->setError('bankID', 'BANK_ID_EMPTY');
                return;
            }
        }
    }
    
    private function validateIsLoggedIn() {
        $this->isLoggedIn = $this->extractForm($this->arguments, "isLoggedIn");
        if (empty($this->isLoggedIn) || $this->isLoggedIn == 0) {
            $this->isLoggedIn = false;
        } else
            $this->isLoggedIn = true;
    }
    
    private function validateIsEmployee() {
        $this->isEmployee = $this->extractForm($this->arguments, "isEmployee");
        if (empty($this->isEmployee) || $this->isEmployee == 0) {
            $this->isEmployee = false;
        } else
            $this->isEmployee = true;
    }
    
    private function validateDateCreated() {
        $this->dateCreated = $this->extractForm($this->arguments, "dateCreated");
    }
    
    // implement JsonSerializable interface - this determins what json_encode() returns with a Profile object as the argument
    public function jsonSerialize() {
        $object = new stdClass();
        $object->profileID = $this->profileID;
        $object->email = $this->email;
        $object->phone = $this->phone;
        $object->gender = $this->gender;
        $object->dob = $this->dob;
        $object->address = $this->address;
        $object->password = $this->password;
        $object->isLoggedIn = $this->isLoggedIn;
        $object->isEmployee = $this->isEmployee;
        $object->passwordChanged = $this->passwordChanged;
        
        return $object;
    }
}
?>