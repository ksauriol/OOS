<?php
class ProfileController {
    
    private static $jsonReply = '';
    const NUM_PROFILE_ARGS = 10; // number of arguments needed to create a profile
    const CODE_SUCCESS = 200;
    const CODE_BAD_REQUEST = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_NOT_FOUND = 404;
    const CODE_INTERNAL_SERVER_ERROR = 500;
    const CAUSE_TIME_OUT= 'Password timed out';
    const CAUSE_INVALID_ACTION = 'Action argument invalid';
    
    public static function run($arguments = null) {
    
    	// determine requested action
    	$action = array_shift($arguments);
    	//print_r($arguments);
    	//$_SESSION['arguments'] = $arguments;
    	switch ($action) {
    		case 'create':		SignupController::run($arguments);break;
    		case 'password':	self::changePassword($arguments);break;
    		default: View::run();
    		// self::echoMessage('failed', "$action is not a valid action for profiles.");
    		return;
    	}
    
    	View::run();
    }
    
    public static function changePassword($arguments){
    	$hardCode = array("old_password","new_password");
    	$parts =  GenericInput::stripInput($arguments[0], $hardCode);
    	$email = $parts[0];
    	$ProfileDB= ProfilesDB::getProfileBy('email',$parts[0]);
    	
    	if (!is_null($ProfileDB)){
    		
    		if ($ProfileDB->getTimeOfTemp() == 0 ){
    		//	print_r($parts[0]."<br>".$parts[1]."<br>".$parts[2]."<br>");
    			if (strcmp($ProfileDB->getPassword(),$parts[1])==0){
    				$ProfileDB->setPassword($parts[2]);
    				ProfilesDB::editProfile($ProfileDB);
    				self::outputMessage(self::CODE_SUCCESS,'Password Set','Password is Set');
    			} else self::outputMessage(self::CODE_BAD_REQUEST, 'Incorrect email/password', 'Passwrod or Email was incorrect. ');
    		} else if ($ProfileDB->getTimeOfTemp() > time()){
    			//print_r(time().'***'.$ProfileDB->getTimeOfTemp());
    			if (strcmp($ProfileDB->getTemp(),$parts[1])==0){
    				$old = new Profile($ProfileDB->getParameters());
    				$ProfileDB->setPassword($parts[2]);
    				$ProfileDB->setTimeOfTemp(0);
    				//print_r($ProfileDB->__toString());
    				ProfilesDB::editProfile($ProfileDB);
    				self::outputMessage(self::CODE_SUCCESS,'Password Set','Password is Set');
    			}else self::outputMessage(self::CODE_BAD_REQUEST, 'Incorrect email/password', 'Passwrod or Email was Incorrect. ');
    		} else {
    		//	print_r(time().'***'.$ProfileDB->getTimeOfTemp());
    			AccountsDB::deleteAccountsBy('profileID',$ProfileDB->getProfileID());
    			ProfilesDB::deleteProfileBy('email',$parts[0]);
    			self::outputMessage(self::CAUSE_TIME_OUT,'Password timed out',"Account Exceeded Temporary Password Time. Please Create the Account again.");
    		}
    	} else self::outputMessage(self::CAUSE_INVALID_ACTION,'Account not found','Invalid Account, Account Not Found');
    	
    	
    }
    
    /*
    private static function addProfile($arguments) {
        if (is_null($arguments) || count($arguments) != self::NUM_PROFILE_ARGS) {
            self::echoMessage('failed', 'Arguments missing. Expected ' . (self::NUM_PROFILE_ARGS) . ' arguments for adding a profile.');
            return;
        }
        
        $profile = new Profile($arguments); break;
        
        // arguments data had errors
        if ($profile->getErrorCount() > 0) {
            self::echoMessage('failed', 'Input validation failed. Correct any errors and try again.');
            return;
        }
        
        // duplicate detected
        $existingUser = ProfilesDB::getProfileBy('email', $profile->getEmail());
        if (!is_null($existingUser)) {
            $profile->setError('email', 'MEMBER_PROFILE_EXISTS');
            self::echoMessage('Adding profile failed. Correct any errors and try again.');
            return;
        }
        
        // add the profile
        $profileID = ProfilesDB::addProfile($profile);
    
        // add profile to database failed
        if ($profile->getErrorCount() > 0 || $profileID == -1) {
            self::echoMessage('failed', 'Error: Failed to add profile. Try again later.');
            return;
        }
    
        // profile successfully added to database. return success message and profile
        self::addMessage('success', 'The profile was successfully added to the database');
        self::$jsonReply = self::$jsonReply . json_encode($profile);
        
        View::run();
    }
    
    private static function editProfile() {
    	View::run();
    }
    
    private static function removeProfile() {
    	View::run();
    }
    
    private static function echoMessage($status, $message) {
    	View::run();
    }
    
    private static function addMessage($status, $message) {
        $replyMsg = new ReplyMessage();
        $replyMsg->status = $status;
        $replyMsg->message = $message;
        $json = json_encode($replyMsg);
        self::$jsonReply = self::$jsonReply . $json;
    }*/

}
?>