<?php 
/**
 * Missing: how to pass input parameters(the user's email from tables
 * Currently: Skeleton class for email 
 * @author Adam Taylor
 * @version 1.0 
 */

class EmailTest {
	
	/**
	 * Calls mail function, which might be using smtp instead of postfix
	 * @param unknown $receiverEmail
	 * @param unknown $type
	 */
	public static function sendEmail($receiverEmail, $type) {
		$subject="Do Not Reply: Your Temporary Password has been sent";
		$subject2="Do Not Reply: Your Password has been reset"; 
		$subject3="Do Not Reply: Your Password has been changed"; 
		$header="From: OOS";
		$tempPassword=RandomPassword(); 
		$message="Congratulations! Your new account for $receiverEmail has been created. Your temporary password is: $tempPassword"; 
		// end temporary password 
		if ($type == 1) {
			$sentFlag=mail($receiverEmail, $subject, $message);
		} // end if statement 
	} // end send mail function
	
	/**
	 * Generates a temporary, random password for new user or a recently reset account
	 * @return string
	 */
	function tempPasswordGenerator() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
		$charLength = strlen($characters);
		$randomPassword = ''; 
		$length = 9; 
		for ($i = 0; $i < length; $i++) {
			$randomPassword .= $characters(rand(0, $charLength - 1));
		}
		return $randomPassword;
	} // end RandomPassword
	
} // end class EmailTest 
?>