<?php
class Profile extends GenericModelObject {
    
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
   // private $profileType;
    
    //private static $allowedProfileTypes = array('member', 'employee');
    
    const INDEX_FIRST_NAME = 0;
    const INDEX_MIDDLE_NAME = 1;
    const INDEX_LAST_NAME = 2;
    const INDEX_EMAIL = 3;
    const INDEX_PHONE = 4;
    const INDEX_GENDER = 5;
    const INDEX_DOB = 6;
    const INDEX_ADDRESS = 7;
    const INDEX_PROFILE_TYPE = 8;
    const INDEX_PROFILE_ID = 9;
    
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
        		"SSN" => $this->SSN
         //   "profileType" => $this->profileType
        );
        
        return $paramArray;
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
            "SSN : [" .		  $this->SSN 	 . "]\n";
                      //  "Profile type: [" . $this->profileType . "]\n";
        
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
           // $this->profileType = "";
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
        //    $this->validateProfileType();
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
        $this->middleName = $this->extractForm($this->arguments, "middleName");//self::INDEX_MIDDLE_NAME);
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
        $this->lastName = $this->extractForm($this->arguments, "lastName");//self::INDEX_LAST_NAME);
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
        $this->email = $this->extractForm($this->arguments, "email");//self::INDEX_EMAIL);
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
        $this->phone = $this->extractForm($this->arguments, "phone");//self::INDEX_PHONE);
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
        $this->gender = $this->extractForm($this->arguments, "gender");//self::INDEX_GENDER);
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
        $this->address = $this->extractForm($this->arguments, "address");//self::INDEX_ADDRESS);
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
    /*
    private function validateProfileType() {
        $this->profileType = $this->extractForm($this->arguments, "profileType");//self::INDEX_PROFILE_TYPE);
     if (empty($this->profileType)) {
            $this->setError("profileType", "PROFILE_TYPE_EMPTY");
            return;
        }
        
        if (!in_array($this->profileType, self::$allowedProfileTypes)) {
            $this->setError("profileType", "PROFILE_TYPE_INVALID");
            return;
        }
    }*/
}
?>