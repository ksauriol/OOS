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
    
    const NUM_ARGS = 1; // number of arguments expected when calling this controller
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
            self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_WRONG_NUM_ARGS, 'Expected arguments email and password.');
            return;
        }
        
        // determine action requested
        $action = array_shift($arguments);
  
        switch ($action) {
            case 'login': self::login(); break;
            case 'logout': self::logout(); break;
            default:
                self::outputMessage(self::CODE_BAD_REQUEST, self::CAUSE_INVALID_ACTION, "The first argument should be either 'login' or 'logout'. Argument '$action' invalid.");
                return;
        }
        
    }
   
    private static function login() {
        // authorize request
        if ( ($profile = self::verifyMember()) === false)
            return;
        
        // check if member is already logged in
        if ($profile->isLoggedIn()) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'Member already logged in.', 'The member was already logged in.');
            return;
        }
        
        // log member in
        if (session_start() === false || ( $profile = ProfilesDB::logIn($profile->getProfileID()) ) === false) {
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, self::CAUSE_SESSION_CREATE_FAILED, 'Login attempt should have succeeded, but failed for an unknown reason.');
            return;
        }
        
        // user successfully logged in. record that the user has been authenticated, and return success message
        if (isset($_SESSION))
            $_SESSION['authenticated'] = true; // want to know if someone is logged in, in the future? check that (isset($_SESSION) && $_SESSION['authenticated']) evaluates to true
        self::outputMessage(self::CODE_SUCCESS, self::CAUSE_LOGGED_IN, 'Log in successful', $profile);
    }
    
    // no arguments expected beyond the original /logout argument that caused this function to be called
    private static function logout() {
        // authorize request
        if ( ($profile = self::verifyMember()) === false)
            return;
        
        // check if use was logged in
        if (! $profile->isLoggedIn()) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'Member not in.', 'The member was not logged in.');
            return;
        }
        
        // log out
        if (isset($_SESSION)) {
            unset($_SESSION['authenticated']); // just in case
            $result = session_destroy();
            session_regenerate_id(true);
        }
        
        if (ProfilesDB::logOut($profile->getProfileID()) === false) {
            self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, 'Logout error', 'Logout attempt should have succeeded, but failed for an unknown reason.');
            return;
        }
        
        // log out successful. return success message
        self::outputMessage(self::CODE_SUCCESS, self::CAUSE_LOGGED_OUT, 'User successfully logged out.');
    }
    
    // returns Profile object of member on success, or false on failure
    private static function verifyMember() {
        if (!isset($_GET['email']) || !isset($_GET['password'])) {
            self::outputMessage(self::CODE_BAD_REQUEST, 'Missing email or password', 'Argument "email" and "password" expected.');
            return false;
        }
    
        // retreive member data from database
        $profile = ProfilesDB::getProfileBy('email', $_GET['email']);
        if (is_null($profile)) {
            /* TODO modify ProfilesDB to return different values on error and when no matching profile is found, then swap output message below
             * I didn't do it already, because ProfilesDB is used by non-gps-related classes, and I don't want to break them. */
            //             self::outputMessage(self::CODE_INTERNAL_SERVER_ERROR, 'Failed to verify GPS data', 'An internal error occured. Try again later.');
            self::outputMessage(self::CODE_UNAUTHORIZED, 'Authorization failed.', 'Incorrect email or password.');
            return false;
        }
    
        // make sure member has set a password
        if (empty($profile->getPassword())) {
            self::outputMessage(self::CODE_UNAUTHORIZED, 'Member password not set.', 'A password must be set before the requested action can be performed.');
            return false;
        }
    
        // verify
        if ($_GET['password'] !== $profile->getPassword()) {
            self::outputMessage(self::CODE_UNAUTHORIZED, 'Authorization failed.', 'Incorrect email or password.');
            return false;
        }
    
        return $profile;
    }
  
    // outputs a json-encoded response according to the RESTful API (I think)
    private static function outputMessage($code, $cause, $description, $data = null) {
        $replyMsg = new JSONResponse($code, $cause, $description, $data);
        $replyMsg->meta = new meta();
        $replyMsg->meta->code = $code;
        $replyMsg->meta->cause = $cause;
        $replyMsg->meta->description = $description;
        $replyMsg->data = $data; // specific to endpoints. may be null for some endpoints.
        $json = json_encode($replyMsg, JSON_PRETTY_PRINT);
        
        if(isset($_GET['debug']))
            echo '<pre>' . $json . '</pre>';
        else
            echo $json;
    }
    
}
?>