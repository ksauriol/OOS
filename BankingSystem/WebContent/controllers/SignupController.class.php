<?php
// YEAH THIS AIN'T DONE YET
class SignupController {
    
	private static $jsonReply = '';
	const CODE_SUCCESS = 200;
	const CODE_BAD_REQUEST = 400;
	const CODE_UNAUTHORIZED = 401;
	const CODE_NOT_FOUND = 404;
	const CODE_INTERNAL_SERVER_ERROR = 500;
	
	public static function run($arguments = null) {
		if (is_null($arguments)) {
		    self::outputMessage(self::CODE_BAD_REQUEST, 'Missing action', 'An action must be specified after /account/');
		    return;
		}
		
		$action = array_shift($arguments);
		
		switch ($action) {
		    case 'create': self::createProfile($arguments); break;
		    case 'password': self::changePassword($arguments);break;
		    default: echo 'error. action: ' . $action;
		}
	}
	
	private static function createProfile($arguments) {
	    
	    // check arguments
	  
	    if (!array_key_exists(0, $arguments) || !isset($_GET['ssn']) || !isset($_GET['name']) || !isset($_GET['email'])) {
	        self::outputMessage(self::CODE_BAD_REQUEST, 'Missing arguments', 'bankID, SSN, name, and email are required for registration.');
	        return;
	    }
	    list($_GET['firstName'], $_GET['lastName']) = explode(' ', $_GET['name']);
	    $bankID = $arguments[0];
	    
	    // make sure an account with the specified bankID (aka accountID) exists
	    $matchingAccounts = AccountsDB::getAccountsBy('bankID', $bankID);
	    if (empty($matchingAccounts)) {
	        self::outputMessage(self::CODE_BAD_REQUEST, 'Member not found', 'A member with the specified bank ID does not exist.');
	        return;
	    }
	    
	    // make sure the profile has not already been created
	    $existingProfile = ProfilesDB::getProfileBy('email', $_GET['email']);
	    if (!is_null($existingProfile)) {
	        self::outputMessage(self::CODE_BAD_REQUEST, 'Account already exists', 'An account with the specified bank ID already exists.');
	        return;
	    }
	    
	    // generate default password for account, and store bank ID
	    $_GET['password'] =  Email::sendEmail( $_GET['email'], 1);
	    $_GET['bankID'] = $bankID; 
	    
	    // create the profile
	    $profile = new Profile($_GET);
	    if ($profile->getErrorCount() > 0) {
	    	
	        self::outputMessage(self::CODE_BAD_REQUEST, 'Account creation failed', 'Errors occured while processing the arguments to create the account.');
	        return;
	    }
	    
	    // store the profile in the database
	    $result = ProfilesDB::addProfile($profile);
	    if ($profile->getErrorCount() > 0 || !is_numeric($result)) {
	        self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, 'Account creation failed', 'Errors occured while attempting to store the new account information in the database.');
	        return;
	    }
	    $profile->setProfileID($result);
	    
	    // success
	   
	    self::outputMessage(self::CODE_SUCCESS, 'Registration complete', 'An account for specified member was successfully created.', $profile);
	}
	
	private static function changePassword($arguments){
		//check input make sure correct
		if (!array_key_exists(0, $arguments) || !isset($_GET['old_password']) || !isset($_GET['new_password'])) {
			self::outputMessage(self::CODE_BAD_REQUEST, 'Missing arguments', 'email,old_password, new_password' );
			return;
		}
	
		$email = $arguments[0];
		//grab the profile by email given --First Argument--
		$matchingProfile = ProfilesDB::getProfileBy('email', $email);
		if (empty($matchingProfile)) {
			self::outputMessage(self::CODE_BAD_REQUEST, 'Member not found', 'A member with the specified email does not exist.');
			return;
		}
		//Change the password
		
		
		
		if ($matchingProfile->getPassword() == $_GET['old_password']){
			
			//put the new password in
			$matchingProfile->setPassword($_GET['new_password']);
			//This is for changing the temporary password
			if (!$matchingProfile->getPasswordChanged()){
				
				if (time() < strtotime($matchingProfile->getDateCreated()) + 900000){
					$matchingProfile->setPasswordChanged(true);
				} else { //The 15 minute expired
					self::outputMessage(self::CODE_BAD_REQUEST, 'Profile Expired', 'The Profile already exceeded the 15 minute window to change temporary password.');
					ProfilesDB::deleteProfileBy('email',$email);
					return;
				}
			}
			//success
			Email::sendEmail($email, 3);
			ProfilesDB::editProfile($matchingProfile);
			self::outputMessage(self::CODE_SUCCESS, 'Password Changed', 'Your password was changed correctly', $matchingProfile);
		} else {
			self::outputMessage(self::CODE_BAD_REQUEST, 'Authorization failed.', 'Incorrect email or password.');
			return;
		}
	
	}
	private static function generatePassword() {
	    return 'password'; // this will change eventually
	}
	    
	// outputs a json-encoded response according to the RESTful API (I think)
	private static function outputMessage($code, $cause, $description, $data = null) {
	    $object = new stdClass();
	    $object->meta = new stdClass();
	    $object->meta->code = $code;
	    $object->meta->cause = $cause;
	    $object->meta->description = $description;
	    $object->data = $data; // specific to endpoints. may be null for some endpoints.
	    $json = json_encode($object, JSON_PRETTY_PRINT);
	
	    if(isset($_GET['debug']))
	        echo '<pre>' . $json . '</pre>';
        else
            echo $json;
	}
	
}
?>
			                                