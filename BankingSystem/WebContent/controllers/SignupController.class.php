<?php
// YEAH THIS AIN'T DONE YET
class SignupController {
    
	private static $jsonReply = '';
	const NUM_ARGS = 3;
	// TODO addMessage plus return does nothing. fix this
	
	
	public static function run($arguments = null) {
		if (is_null($arguments) || count($arguments) != self::NUM_ARGS) {
			//   self::addMessage('failed', 'Arguments missing. Expected ' . self::NUM_ARGS. ' arguments for profiles.');
			//      return;
		}
		// print_r($arguments);
		$hardCode = array("ssn","name","email","middleName","lastName","phone","gender","address", "dob");
		$parts =  GenericInput::stripInput($arguments[0], $hardCode);
		$num =intval($parts[0]);
		$parts[0]=$num;
		// print_r($arguments);
		if ($parts[0]<0){
			print_r("Error. accountID cannot be smaller or equal to 0<br>");
		} else if ($parts[0]>999999999){
			print_r("Error. accountID cannot be that large<br>");
		} else
			$account = AccountInfoDB::getAccountsBy('accountID', $parts[0]);
		// print_r($parts[1].'<br>'.$parts[2]);
		if (!is_null($account[0])){
			//  print_r($account[0]->getProfileID().'NULL<br>');
			/*
			if (($account[0]->getProfileID()>= 1 )){
				//print_r($account[0]->getAccountID().'NULL<br>');
				//echo $account->getAccountID().'INT<br>';
				print_r(" Error. That Account already has a profile<br>");
				//View::run();
				return;
			}*/
			
			if ($account[0]->getSSN() != intval($parts[1]) ){
				//print_r(intval($parts[1]).'<br>'.$account[0]->getSSN().'<br>');
				print_r("Error. Wrong Account Authentication.");
				return;
			}
			$param = array();
			//print_r(intval($parts[1]).'<br>');
			$param['SSN'] =  intval($parts[1]);
			$param['firstName'] = $parts[2];
			$param['email'] = $parts[3];
			$param['temp'] ="password";
			$param['timeOfTemp']=time()+600;
			
			
			if(!is_null($parts[4]))
				$param['middleName']=$parts[4];
			if(!is_null($parts[5]))
				$param['lastName']=$parts[5];
			if(!is_null($parts[6]))
				$param['phone']=intval($parts[6]);
			if(!is_null($parts[7]))
				$param['gender']=$parts[7];
			if(!is_null($parts[8]))
				$param['address']=$parts[8];
			if(!is_null($parts[9]))
				$param['dob']=$parts[9];
			/*
			$newProfile = new Profile(array(
					"SSN"		 => intval($parts[1]),
					"firstName"  => $parts[2],
					"temp"       => "password",
					"timeOfTemp" => time()+600,
					"email"      => $parts[3]
			));*/
			$newProfile = new Profile($param);
			//print_r($newProfile.'<br>');
			$ProfileDB= ProfilesDB::getProfileBy('email',$parts[3]);
			if (is_null($ProfileDB)){
				$ProfileDB= ProfilesDB::getProfileBy('SSN',$parts[1]);
				if (is_null($ProfileDB)){
					ProfilesDB::addProfile($newProfile);
					$ProfileDB= ProfilesDB::getProfileBy('email',$parts[3]);
				} else {
					print_r("Incorrect SSN/email combination");
					return;
				}
			}
			//        print_r($ProfileDB->getProfileID().'<br>');
	
			// print_r($account[0]->getAccountID().'<br>');
			$account[1] = new Account(array(
					"accountID" => $account[0]->getAccountID(),
					"profileID" => $ProfileDB->getProfileID(),
					"SSN"		=> $ProfileDB->getSSN()
			));
			
			//SSN of Account and profile dont match 
			/*
			if ($ProfileDB->getSSN() != $account[0]->getSSN()){
				print_r("Error. Wrong Account Authentication.");
				return;
			}*/
			//print_r($account[1]->getAccountID().'<br>'.$account[1]->getProfileID().'<br>');
			AccountsDB::addAccount($account[1]);
			}else print_r("Error. Bank Account does not exist");
	}
			/*
			 private static function addMessage($status, $message) {
			 $replyMsg = new ReplyMessage();
			 $replyMsg->status = $status;
			 $replyMsg->message = $message;
			 $json = json_encode($replyMsg);
			 self::$jsonReply = self::$jsonReply . $json;
			 }*/
			
}
?>
			                                