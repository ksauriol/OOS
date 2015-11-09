<?php
/* NOTE: I wrote this controller with the following assumptions:
 * 1: A class called ProfilesDB exists with method getProfileBy($attributeName, $attributeValue) that returns
 *    a Profile object when $attributeName equals 'email' and $attributeValue is a valid user email address.
 * 2: Profile objects have a getPassword() method that returns the profile's password (hash).
 * 3: The input in $arguments is a numerically indexed array, with:
 *     $arguments[0] is an action: either 'login' or 'logout'
 *     $arguments[1] is email, and is only present for login action
 *     $arguments[2] is password, and is only present for login action. It is currently directly compared to the value stored in the database.
 */
class LoginController {
    
    const NUM_ARGS = 3; // number of arguments expected when calling this controller
    const CODE_SUCCESS = 200;
    const CODE_BAD_REQUEST = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_NOT_FOUND = 404;
    const CODE_INTERNAL_SERVER_ERROR = 500;
    const CAUSE_WRONG_NUM_ARGS = 'Wrong number of arguments received';
    const CAUSE_INVALID_ACTION = 'Action argument invalid';
    const CAUSE_MISSING_LOGIN_ARGS = 'Arguments for login action not found';
    const CAUSE_WRONG_NUM_LOGIN_ARGS = 'Wrong number of arguments received for login action';
    const CAUSE_PROFILE_NOT_FOUND = 'Profile not found';
    const CAUSE_PROFILE_ERRORS = 'Error reading profile from database';
    const CAUSE_INVALID_PASSWORD = 'Password invalid';
    const CAUSE_USER_ALREADY_LOGGED_IN = 'User already logged in';
    const CAUSE_SESSION_CREATE_FAILED = 'Failed to create session';
    const CAUSE_LOGGED_IN = 'Logged in';
    const CAUSE_NOT_LOGGED_IN = 'User is not logged in';
    const CAUSE_SESSION_DESTROY_FAILED = 'Failed to destroy session';
    const CAUSE_SESSION_REGERATION_FAILED = 'Logout successful, but failed to regenerate session ID';
    const CAUSE_LOGGED_OUT = 'Logged out';
    
    // arguments expected: /login/email/password or /logout (main controller already put these fields in an array)
    public static function run($arguments = null) {
        if (is_null($arguments) || count($arguments) != self::NUM_ARGS) {
         //   self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_WRONG_NUM_ARGS, 'Arguments expected: /login/email/password or /logout');
           // return;
        }
        
        // determine action requested
        $action = array_shift($arguments);
      //  print_r($arguments[0].'<br>');
  
        switch ($action) {
            case 'login': self::login($arguments); break;
          case 'logout': self::logout(); break;
            default:
            self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_INVALID_ACTION, "The first argument should be either 'login' or 'logout'. Argument '$action' invalid.");
                return;
        }
        
    }
   
    private static function login($arguments) {
        if (is_null($arguments)) {
            self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_MISSING_LOGIN_ARGS, 'Additional arguments for login not found. Arguments expected for login: /login/email/password');
            return;
        }

        $argc = count($arguments);
        if ($argc != 2) {
            self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_WRONG_NUM_LOGIN_ARGS, "Argument 'login' found. 2 more arguments expected for login. Only $argc more arguments received.");
            return;
        }
        
        $email = array_shift($arguments);
        $password = array_shift($arguments);
        
        // make sure a profile with the given email exists
        $profile = ProfilesDB::getProfileBy('email', $email);
       
        if (is_null($profile)) {
        	
            self::outputMessage(self::CODE_NOT_FOUND, self::CAUSE_PROFILE_NOT_FOUND, 'A profile with the specified email does not exist.');
            return;
        }
      //  print_r($profile.'<br>');
        // make sure the profile was loaded from the database without errors
        if ($profile->getErrorCount() > 0) {
        	//print_r($profile.'<br>');
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, self::CAUSE_PROFILE_ERRORS, 'An error occured while loading the specified profile from the database.');
        }
      
        /* make sure password is correct. this assumes that either both the given password and
         * the stored password are hashed, or they are both un-hashed (i.e. they are just compared directly). */
        if ($profile->getPassword() !== $password) {
            self::outputMessage(self::CODE_UNAUTHORIZED, self::CAUSE_INVALID_PASSWORD, 'The specified password is incorrect');
            return;
        }
      
        // at this point, password has been verified. make sure user is not already logged in
        if (session_status() == PHP_SESSION_ACTIVE)  {
            self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_USER_ALREADY_LOGGED_IN, 'The user is already logged in, and therefore cannot be logged in again.');
            return;
        }
       // print_r($profile.'<br>');
        // everything checks out now. log them in
        if (session_start() === false) {
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, self::CAUSE_SESSION_CREATE_FAILED, 'Login attempt should have succeeded, but failed for an unknown reason.');
            return;
        }
        
        // user successfully logged in. record that the user has been authenticated, and return success message
        $_SESSION['authenticated'] = true; // want to know if someone is logged in, in the future? check that (isset($_SESSION) && $_SESSION['authenticated']) evaluates to true
        print_r("SUCCESS. Logged in as: ".$profile->getFirstName().'<br>');
        self::outputMessage(self::CODE_SUCCESS, self::CAUSE_LOGGED_IN, 'Log in successful');
    }
    
    // no arguments expected beyond the original /logout argument that caused this function to be called
    private static function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_NOT_LOGGED_IN, 'User cannot log out because user was not logged in');
            return;
        }
        
        // log out
        unset($_SESSION['authenticated']); // just in case
        $result = session_destroy();
        if ($result === false) {
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, self::CAUSE_SESSION_DESTROY_FAILED, 'User was logged in, but logging out failed.');
            return;
        }
        if (session_regenerate_id(true) === false) {
            self::outputMessage(self::CODE_SUCCESS, self::CAUSE_SESSION_REGERATION_FAILED, "User logged out, but failed to regenerate session ID. The next login attempt may fail.");
            return;
        }
        
        // log out successful. return success message
        self::outputMessage(self::CODE_SUCCESS, self::CAUSE_LOGGED_OUT, 'User successfully logged out.');
    }
  
    // outputs a json-encoded response according to the RESTful API (I think)
    private static function outputMessage($code, $cause, $description, $data = null) {
        $replyMsg = new JSONResponse($code, $cause, $description, $data);
        $replyMsg->meta = new meta();
        $replyMsg->meta->code = $code;
        $replyMsg->meta->cause = $cause;
        $replyMsg->meta->description = $description;
        $replyMsg->data = $data; // specific to endpoints. may be null for some endpoints.
        $json = json_encode($replyMsg);
    }
    
   /* old function
    private static function outputMessage($status, $message) {
        $replyMsg = new ReplyMessage();
        $replyMsg->status = $status;
        $replyMsg->message = $message;
        $json = json_encode($replyMsg);
        echo $json;
    }*/
    
}
?>