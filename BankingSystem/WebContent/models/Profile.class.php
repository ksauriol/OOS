<?php
class Profile extends GenericModelObject implements JsonSerializable {
    
    private $arguments;
    private $profileID;
    private $firstName;
    private $middleName;
    private $lastName;
    private $email;
    private $phone;
    private $gender;
    private $dob;
    private $address;
    private $SSN;
    private $timeOfTemp;
    private $temp;
    private $password;
    private $isLoggedIn;
    
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
    
    public function setTimeOfTemp($input) {
    	$this->arguments['timeOfTemp']=$input;
    	$this->validateTimeOfTemp();
    }
    
    public function setTemp($input) {
    	$this->arguments['temp']=$input;
    	$this->validateTemp();
    }
    
    public function setPassword($input) {
    	$this->arguments['password']=$input;
		$this->validatePassword();
    }
    
    public function getSSN() {
    	return $this->SSN;
    }
    
    public function getTimeOfTemp() {
    	return $this->timeOfTemp;
    }
    
    public function getProfileID() {
        return $this->profileID;
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
    
    public function getAddress() {
        return $this->address;
    }
    /*
    public function getProfileType() {
        return $this->profileType;
    }*/
    
    // Returns data fields as an associative array
    public function getParameters() {
        $paramArray = array(
            "profileID" => $this->profileID,
            "firstName" => $this->firstName,
            "middleName" => $this->middleName,
            "lastName" => $this->lastName,
            "email" => $this->email,
            "phone" => $this->phone,
            "gender" => $this->gender,
            "address" => $this->address,
            "dob" => $this->dob,
        	"temp" => $this->temp,
        	"password" => $this->password,
        	"timeOfTemp" => $this->timeOfTemp,
    		"SSN" => $this->SSN,
            "isLoggedIn" => $this->isLoggedIn
        );
        
        return $paramArray;
    }
    
    public function isLoggedIn() {
        return $this->isLoggedIn;
    }
    
    public function __toString() {
        $str =
            "Profile ID: [" . $this->profileID . "]\n" .
            "First name: [" . $this->firstName . "]\n" .
            "Middle name: [" . $this->middleName . "]\n" .
            "Last name: [" . $this->lastName . "]\n" .
            "E-mail address: [" . $this->email . "]\n" .
            "Phone number: [" . $this->phone . "]\n" .
            "Gender: [" . $this->gender . "]\n" .
            "Address: [" . $this->address . "]\n" .
            "Date of birth: [" . $this->dob . "]\n".
            "temp: [" . $this->temp . "]\n".
            "timeOfTemp: [" . $this->timeOfTemp . "]\n".
            "password: [" . $this->password . "]\n".
            "SSN : [" .		  $this->SSN 	 . "]\n" .
            "Is Logged In: [" . $this->isLoggedIn . "]";
        
        return $str;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->arguments)) {
            $this->profileID = "";
            $this->firstName = "";
            $this->middleName = "";
            $this->lastName = "";
            $this->email = "";
            $this->phone = "";
            $this->gender = "";
            $this->address = "";
            $this->dob = "";
            $this->isLoggedIn = "";
        }
        else {
            $this->validateProfileID();
            $this->validateFirstName();
            $this->validateMiddleName();
            $this->validateLastName();
            $this->validateEmail();
            $this->validatePhone();
            $this->validateGender();
            $this->validateAddress();
            $this->validateDOB();
            $this->validateSSN();
            $this->validateTimeOfTemp();
            $this->validateTemp();
            $this->validatePassword();
            $this->validateIsLoggedIn();
        }
    }
    
    private function validateTemp() {
    	$this->temp = $this->extractForm($this->arguments, "temp");
    }
    
    private function validatePassword() {
    	$this->password = $this->extractForm($this->arguments, "password");
    }
    
    private function validateTimeOfTemp() {
    	$this->timeOfTemp = $this->extractForm($this->arguments, "timeOfTemp");
    }
    
    private function validateSSN() {
    	$this->SSN = $this->extractForm($this->arguments, "SSN");
    }
    
    private function validateProfileID() {
        $this->profileID = $this->extractForm($this->arguments, "profileID");
    }
    
    private function validateFirstName() {
        $this->firstName = $this->extractForm($this->arguments, "firstName");
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
        $this->middleName = $this->extractForm($this->arguments, "middleName");
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
        $this->lastName = $this->extractForm($this->arguments, "lastName");
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
    
    private function validateIsLoggedIn() {
        $this->isLoggedIn = $this->extractForm($this->arguments, "isLoggedIn");
        if (empty($this->isLoggedIn) || $this->isLoggedIn == 0) {
            $this->isLoggedIn = false;
        } else
            $this->isLoggedIn = true;
    }
    
    // implement JsonSerializable interface - this determins what json_encode() returns with a Profile object as the argument
    public function jsonSerialize() {
        $object = new stdClass();
        $object->profileID = $this->profileID;
        $object->firstName = $this->firstName;
        $object->middleName = $this->middleName;
        $object->lastName = $this->lastName;
        $object->email = $this->email;
        $object->phone = $this->phone;
        $object->gender = $this->gender;
        $object->dob = $this->dob;
        $object->address = $this->address;
        $object->SSN = $this->SSN;
        $object->timeOfTemp = $this->timeOfTemp;
        $object->temp = $this->temp;
        $object->password = $this->password;
        $object->isLoggedIn = $this->isLoggedIn;
        
        return $object;
    }
}
?>