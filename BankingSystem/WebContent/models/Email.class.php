<?php 
/**
 * Missing: how to pass input parameters(the user's email from tables
 * Currently: Skeleton class for email 
 * @author Adam Taylor
 * @version 1.0 
 */

class Email {
	
	/**
	 * Calls mail function, which might be using smtp instead of postfix
	 * @param unknown $receiverEmail
	 * @param unknown $type
	 */
	public static function sendEmail($receiverEmail, $type) {
		$header="From: OOS";
		if ($type == 1) {
			$subject="Do Not Reply: Your Temporary Password has been sent";
			$tempPassword= Self::tempPasswordGenerator();
			$message="Congratulations! Your new account for $receiverEmail has been created. Your temporary password is: $tempPassword";
			$sentFlag=mail($receiverEmail, $subject, $message);
			return $tempPassword;
		} elseif ($type ==2){
			$tempPassword= Self::tempPasswordGenerator();
			$subject="Do Not Reply: Your Password has been reset";
			$message="Your account has been reset. Your temporary password is: $tempPassword";
			$sentFlag=mail($receiverEmail, $subject, $message);
			return $tempPassword;
		} elseif ($type ==3){
			$subject="Do Not Reply: Your Password has been changed";
			$message="Do Not Reply: Your Password has been changed";
			$sentFlag=mail($receiverEmail, $subject, $message);
			return;
		}
	
	} // end send mail function
	
	/**
	 * Generates a temporary, random password for new user or a recently reset account
	 * @return string
	 */
	private function tempPasswordGenerator() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charLength = strlen($characters);
		$randomPassword = ''; 
		$length = 9; 
		for ($i = 0; $i < $length; $i++) {
			$randomPassword .= $characters[rand(0, $charLength - 1)];
		}
		return $randomPassword;
	} // end RandomPassword
	
} // end class EmailTest 
?>